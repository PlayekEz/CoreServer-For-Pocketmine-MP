<?php

namespace Playek\Core\commands;

use pocketmine\Player;

use pocketmine\command\CommandSender;

use Playek\Core\Main;
use Playek\Core\utils\Permissions;
use Playek\Core\utils\Utils;

class WpCommand extends \pocketmine\command\PluginCommand {
	
	private $main, $prefix = "§e§lᴡᴘ§7› ";
	
	public function __construct(Main $main){
		parent::__construct("wp", $main);
		$this->setDescription("WorldProtect For Playover!");
		$this->main = $main;
	}
	
	public function execute(CommandSender $sender, string $label, array $args): bool {
		if(!$sender->hasPermission(Permissions::WP_CMD_USE)){
			$sender->sendMessage(Utils::MESSAGE_NP);
			return false;
		}
		if(!isset($args[0])){
			$sender->sendMessage("§cUsa /wp help");
			return false;
		}
		switch($args[0]){
			case "protect": //Args[1] => world
			if(!isset($args[1])){
				$sender->sendMessage($this->prefix."Usa /wp protect <world>");
				return false;
			}
			if(Utils::isAlreadyExistsWP($args[1])){
				$sender->sendMessage($this->prefix."§cEste mundo ya ha sido protegido antes");
				return false;
			}
			Utils::protectWorld($args[1]);
			$sender->sendMessage($this->prefix."§aSe ha protegido el mundo §7{$args[1]} §asastifactoriamente");
			break;
			case "cancel":
			if(!isset($args[1])){
				$sender->sendMessage($this->prefix."§cUsa /wp cancel <world>");
				return false;
			}
			if(!Utils::isAlreadyExistsWP($args[1])){
				$sender->sendMessenger($this->prefix."§cEste mundo no ha sido protegido por §a'WorldProtect'");
				return false;
			}
			Utils::unProtect($args[1]);
			$sender->sendMessage("§aEl Mundo §7{$args[1]} §aha sido des-protegido");
			break;
			case "pvp":
			if(!isset($args[1]) && !isset($args[2])){
				$sender->sendMessage($this->prefix."§cUsa /wp pvp <on | off> <world>");
				return false;
			}
			if(!Utils::isAlreadyExistsWP($args[1])){
				$sender->sendMessage($this->prefix."§cEl mundo debe estar protegido primero");
				return false;
			}
			$Switch = false;
			if($args[2] == "on"){
				$Switch = true;
			}
			Utils::modifyDataWP($args[1], "pvp", $Switch);
			$sender->sendMessage($this->prefix."§aSe ha actualizado el pvp a §7{$Switch}");
			break;
			case "build":
			if(!isset($args[1]) && !isset($args[2])){
				$sender->sendMessage($this->prefix."§cUsa /wp build <on | off> <world>");
				return false;
			}
			if(!Utils::isAlreadyExistsWP($args[1])){
				$sender->sendMessage($this->prefix."§cEl mundo debe estar protegido primero");
				return false;
			}
			$Switch = false;
			if($args[2] == "on"){
				$Switch = true;
			}
			Utils::modifyDataWP($args[1], "build", $Switch);
			$sender->sendMessage($this->prefix."§aSe ha actualizado la construccion a §7{$Switch}");
			break;
		}
		return true;
	}
}
?>