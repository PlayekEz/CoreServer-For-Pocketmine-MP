<?php

namespace Playek\Core\commands;

use pocketmine\Player;

use Playek\Core\Main;
use Playek\Core\utils\Permissions;

class ChatColorCommand extends \pocketmine\command\PluginCommand {
	
	private $main;
	
	public function __construct(Main $main){
		parent::__construct("cc", $main);
		$this->main = $main;
		$this->setDescription("Allow's chat color for overman rank!");
	}
	
	public function execute(\pocketmine\command\CommandSender $sender, string $label, array $args) : bool {
		if(!$sender instanceof Player){
			$sender->sendMessage("§cUsa este comando solo dentro del servidor!");
			return false;
		}
		if(!$sender->hasPermission(Permissions::CC_CMD_USE)){
			$sender->sendMessage("§cNo tienes permisos para utilizar este cosmetico!");
			return false;
		}
		if(!isset($this->main->chatColor[$sender->getName()])){
			$sender->sendMessage("§3» §7Has activado el chat a color!");
			$this->main->chatColor[$sender->getName()] = true;
		}else{
			$sender->sendMessage("§3» §7Has desactivado el chat a color");
			unset($this->main->chatColor[$sender->getName()]);
		}
		return true;
	}
}
?>