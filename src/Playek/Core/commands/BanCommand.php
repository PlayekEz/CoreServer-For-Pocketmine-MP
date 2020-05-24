<?php



namespace Playek\Core\commands;



use pocketmine\command\CommandSender;

use pocketmine\Server;

use pocketmine\Player;



use Playek\Core\Main;

use Playek\Core\api\Punish;

use Playek\Core\utils\Permissions;



class BanCommand extends \pocketmine\command\PluginCommand {

	

	private $main;

	

	public function __construct(Main $main){

		parent::__construct("punish", $main);

		$this->main = $main;

	}

	

	public function execute(CommandSender $sender, string $label, array $args): bool {
		$server = $this->main->getServer();
		if(!$sender->hasPermission(Permissions::BAN_CMD_USE)){

			$sender->sendMessage("§cNo tienes permisos para usar este comando");

			return false;

		}

		if(!isset($args[0])){

			$sender->sendMessage("§cUsa /punish help");

			return false;

		}

		if($args[0] == "cancel"){

			if(!isset($args[1])){

				$sender->sendMessage("§cEspecifica un usuario a desbanear");

				return false;

			}

			unset($args[0]);

			$name = implode(" ", $args);

			if(!Punish::isBanned($name)){

				$sender->sendMessage("§cEl usuario '{$name}' no ha sido baneado");

				return false;

			}

			Punish::cancel($name);

			$sender->sendMessage("§aEl usuario '{$name}' ha sido desbaneado");

		}else{

			/* /punish <player> <time> <reason> */

			if(!isset($args[0])){

				$sender->sendMessage("§cEspecifica un usuario para banear");

				return false;

			}

			$player = Server::getInstance()->getPlayer($args[0]);

			if(!$player instanceof Player){

				$player = Server::getInstance()->getOfflinePlayer($args[0]);

			}

			if(Punish::isBanned($player->getName())){

				$sender->sendMessage("§cEl usuario '{$player->getName()}' ya ha sido baneado antes");

				return false;

			}

			if(!isset($args[1])){

				$sender->sendMessage("§cEspecifica el tiempo, si es permanente pon 0");

				return false;

			}

			if(!is_numeric($args[1])){

				$sender->sendMessage("§cIngresa un valor numero en el argumento 2");

				return false;

			}

			$valor = $args[1];

			if(!isset($args[2])){

				$sender->sendMessage("§cPon una razon por la cual banearas a este usuario");

				return false;

			}

			unset($args[0]);
			
			unset($args[1]);

			$reason = implode(" ", $args);

			if($valor == "0"){

				Punish::register($player->getName(), "BAN", "P", $reason, 0);

				$sender->sendMessage("§cHas baneado a '{$player->getName()}' permanentemente");

				$server->broadcastMessage("§c{$player->getName()} §7ha sido baneado permanentemente debido a §o§8{$reason}");
			}else{

				Punish::register($player->getName(), "BAN", "T", $reason, $valor);

				$sender->sendMessage("§cHas baneado a '{$player->getName()}' por ".$valor." minutos");
				$server->broadcastMessage("§c{$player->getName()} §7ha sido baneado durante §a{$valor} minutos §7debido a §o§8{$reason}");

			}

		}

		return false;

	}

}

			