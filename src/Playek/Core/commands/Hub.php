<?php



namespace Playek\Core\commands;



use pocketmine\Player;

use pocketmine\Server;



use Playek\Core\Main;

use Playek\Core\events\PlayerHubEvent;

use Playek\Core\utils\Utils;



class Hub extends \pocketmine\command\PluginCommand {

	

	private $main;

	

	public function __construct(Main $main){

		parent::__construct("hub", $main);

		$this->setDescription("Return to default lobby");

		$this->main = $main;

	}

	

	public function execute(\pocketmine\command\CommandSender $sender, string $label, array $args): bool {

		if(!$sender instanceof Player){

			$sender->sendMessage("§eHub > §7Solo en el juego funciona este comando");

			return false;

		}

		$ev = new PlayerHubEvent($sender);

		$ev->call();

		if($ev->isCancelled()){

			$sender->sendMessage("§eHub > ".$ev->getMessage());

			return false;

		}

		if(isset($args[0])){
			if(!$sender->isOp()) return false;
			if($args[0] == "setspawn"){

				Utils::setHub($sender);

			}

		}else{

			$level = Utils::getHubLevel();

			if(!$level instanceof Level){

				Server::getInstance()->loadLevel(Utils::getHubLevelToString());

			}

			

			

			$sender->getInventory()->clearAll();

			$sender->getArmorInventory()->clearAll();

			$sender->removeAllEffects();

			$sender->setMaxHealth(20);

			$sender->setHealth(20);

			$sender->setFood(20);
			
			$sender->setGamemode(2);
			
			$sender->setAllowFlight(false);
			
			$sender->setFlying(false);
			$sender->teleport($level->getSpawnLocation());
			Utils::gadgetsLobby($sender);
			$sender->sendMessage("§eHub » §7Bienvenido al lobby ".$sender->getName());

		}

		return false;

	}

}