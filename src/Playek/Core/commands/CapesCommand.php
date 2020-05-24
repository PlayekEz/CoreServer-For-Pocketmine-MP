<?php

namespace Playek\Core\commands;

use pocketmine\Player;

use Playek\Core\Main;
use Playek\Core\utils\Permissions;
use Playek\Core\utils\UI;
use Playek\Core\api\Elo;

class CapesCommand extends \pocketmine\command\PluginCommand {
	
	private $main;
	
	public function __construct(Main $main){
		parent::__construct("capes", $main);
		$this->main = $main;
		$this->setDescription("§oCapas v1.0 para PlayOver");
	}
	
	public function execute(\pocketmine\command\CommandSender $sender, string $label, array $args): bool {
		if(!$sender instanceof Player){
			$sender->sendMessage("§cUsa este comando dentro del servidor!");
			return false;
		}
		if(!isset($args[0])){
			$sender->sendMessage("§cUsa: /capes help");
			return false;
		}
		$api = new \Playek\Core\api\Capes($this->main);
		if($args[0] == "set"){
			if(!isset($args[1])){
				return false;
			}
			switch($args[1]){
				case "energy":
				if(!$sender->hasPermission(Permissions::CAPE_ENERGY)){
					$sender->sendMessage("§cNo tienes permisos para utilizar esta capa!");
					return false;
				}
				$api->setCape($sender, "Energy");
				break;
				case "bc":
				if(!$sender->hasPermission(Permissions::CAPE_BLUECREEPER)){
					$sender->sendMessage("§cNo tienes permisos para utilizar esta capa!");
					return false;
				}
				$api->setCape($sender, "BlueCreeper");
				break;
				case "firework":
				if(!$sender->hasPermission(Permissions::CAPE_FIREWORK)){
					$sender->sendMessage("§cNo tienes permisos para utilizar esta capa!");
					return false;
				}
				$api->setCape($sender, "Firework");
				break;
				case "fire":
				if(!$sender->hasPermission(Permissions::CAPE_FIREWORK)){
					$sender->sendMessage("§cNo tienes permisos para utilizar esta capa!");
					return false;
				}
				$api->setCape($sender, "Fire");
				break;
				case "turtle":
					$elo = new Elo($sender);
					if(!$this->main->iAlreadyBuy($sender->getName(), "cape_turtle")){
						if($elo->buy(520)){
							$this->main->addArticle($sender->getName(), "cape_turtle");
							$api->setCape($sender, "Turtle");
							$sender->sendMessage("§aFelicidades, has comprado esta capa por §e520 Coins");
							return true;
						}
					}else{
						$api->setCape($sender, "Turtle");
						return true;
					}
				break;
				case "rc":
					$elo = new Elo($sender);
					if(!$this->main->iAlreadyBuy($sender->getName(), "cape_rc")){
						if($elo->buy(730)){
							$this->main->addArticle($sender->getName(), "cape_rc");
							$api->setCape($sender, "Red Creeper");
							$sender->sendMessage("§aFelicidades, has comprado esta capa por §e730 Coins");
							return true;
						}
					}else{
						$api->setCape($sender, "Red Creeper");
						return true;
					}
				break;
				case "ig":
					$api->setCape($sender, "Iron Golem");
					return true;
				break;
				case "pickaxe":
					$api->setCape($sender, "Pickaxe");
					return true;
				break;
				case "yt":
					$api->setCape($sender, "Youtube");
					return true;
				break;
				default:
				return false;
				break;
			}
		}
		if($args[0] == "ui"){
			UI::requestUI($sender, UI::ID_MENU_CAPES);
		}
		return true;
	}
}
?>