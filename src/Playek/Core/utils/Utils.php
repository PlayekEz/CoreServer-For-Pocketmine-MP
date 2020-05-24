<?php



namespace Playek\Core\utils;



use pocketmine\Player;

use pocketmine\Server;



use pocketmine\level\Level;

use pocketmine\math\Vector3;

use pocketmine\item\Item;

use Playek\Core\Main;



class Utils {

	

	const MESSAGE_NP = "§cLo sentimos, no tienes permisos para usar este comando!";

	const G_L1 = "§a§oInformacion";
	const G_L2 = "§f§oMinijuegos";
	const G_L3 = "§c§oCosmeticos";

	private static $instance;

	

	public function __construct(Main $main){
		self::$instance = $main;
	}

	public static function gadgetsLobby(Player $p){
		$p->getInventory()->setItem(0, Item::get(339, 0, 1)->setCustomName(self::G_L1));
		$p->getInventory()->setItem(4, Item::get(345, 0, 1)->setCustomName(self::G_L2));
		$p->getInventory()->setItem(8, Item::get(378, 0, 1)->setCustomName(self::G_L3));
	}

	public static function setHub(Player $p){

		$cfg = Main::getInstance()->newConfig("spawns");

		$cfg->set("HubSpawn", [$p->x, $p->y, $p->z, $p->getLevel()->getFolderName()]);

		$cfg->save();

	}

	public static function getHubLevel() {

		$world = Server::getInstance()->getLevelByName("world");

		$cfg = Main::getInstance()->newConfig("spawns");

		if($cfg->get("HubSpawn") != null){

			$world = Server::getInstance()->getLevelByName($cfg->get("HubSpawn")[3]);

		}

		return $world;

	}

	

	public static function getHubLevelToString() {

		$world = "world";

		$cfg = Main::getInstance()->newConfig("spawns");

		if($cfg->get("HubSpawn") != null){

			$world = $cfg->get("HubSpawn")[3];

		}

		return $world;

	}

	

	public static function getHubPosition() {

		$pos = new Vector3(50, 250, 50);

		$cfg = Main::getInstance()->newConfig("spawns");

		if($cfg->get("HubSpawn") != null){

			$pos = new Vector3($cfg->get("HubSpawn")[0], $cfg->get("HubSpawn")[1], $cfg->get("HubSpawn")[2]);

		}

		return $pos;

	}

	

	public static function isPvP(string $world){

		$cfg = Main::getInstance()->newConfig("wp");

		$value = true;

		if($cfg->get($world) != null){

			if(isset($cfg->get($world)["pvp"])){

				$value = $cfg->get($world)["pvp"];

			}

		}

		return $value;

	}

	

	public static function isBuilding(string $world){

		$cfg = Main::getInstance()->newConfig("wp");

		$value = true;

		if($cfg->get($world) != null){

			if(isset($cfg->get($world)["build"])){

				$value = $cfg->get($world)["build"];

			}

		}

		return $value;

	}

	

	public static function protectWorld(string $level){

		$cfg = Main::getInstance()->newConfig("wp");

		$data = [

		"pvp" => false,

		"build" => false

		];

		$cfg->set($level, $data);

		$cfg->save();

	}

	

	public static function unProtect(string $level){

		$cfg = Main::getInstance()->newConfig("wp");

		$cfg->remove($level);

		$cfg->save();

	}

	

	public static function isAlreadyExistsWP(string $level){

		$value = false;

		$cfg = Main::getInstance()->newConfig("wp");

		if($cfg->get($level) != null){

			$value = true;

		}

		return $value;

	}

	

	public static function modifyDataWP(string $level, string $key, $value){

		$cfg = Main::getInstance()->newConfig("wp");

		if($cfg->get($level) != null){

			if(isset($cfg->get($level)[$key])){

				$data = $cfg->get($level);

				$data[$key] = $value;

				$cfg->set($level, $data);

				$cfg->save();

			}

		}

	}

}