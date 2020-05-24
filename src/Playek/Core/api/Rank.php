<?php



namespace Playek\Core\api;



use pocketmine\Player;



use pocketmine\Server;



use Playek\Core\Main;



class Rank {

	

	private $main, $prefix = "§l§eʀᴀɴᴋ › §7";

	public function getPlayers(string $rank, bool $owners = false, string $permission = ""){
		$players = [];
		foreach(Server::getInstance()->getOnlinePlayers() as $p){
			if($this->userRank($p) == $rank){
				$players[$p->getName()] =  $p;
			}
			if($permission != ""){
				if($p->hasPermission($permission)){
					if(!isset($players[$p->getName()])){
						$players[$p->getName()] = $p;
					}
				}
			}
			if($owners){
				if($this->userRank($p) == "owner" or $p->isOp()){
					if(!isset($players[$p->getName()])){
						$players[$p->getName()] = $p;
					}
				}

			}
		}
			
		

		return $players;
	}

	public function __construct(Main $main){

		$this->main = $main;

		self::init();

	}

	

	public static function init(){

		if(!file_exists(Main::getInstance()->getDataFolder() . "ranks.yml")){

			self::create("user", "§a", []);

		}

	}

	

	public static function isExists(string $rank){

		self::init();

		$cfg = Main::getInstance()->newConfig("ranks");

		$list = [];

		foreach($cfg->getAll() as $k => $v){

			$list[$k] = $v;

		}

		$var = false;

		if(isset($list[$rank])){

			$var = true;

		}

		return $var;

	}

	

	public static function getChatFormat(){

		return "§l{color}{rank} §8<§7{name}§8> » {color}{message}";

	}

	

	public static function setChatFormat(string $rank, string $format){

		$data = self::getData($rank);

		if($data == null) return;

		$data["chatFormat"] = $format;

		self::saveData($rank, $data);

	}

	

	public static function getTagFormat(){

		return "§l{color}{rank} §7{name}";

	}

	

	public static function getData(string $rank){

		self::init();

		$cfg = Main::getInstance()->newConfig("ranks");

		$data = null;

		if($cfg->get($rank) != null){

			$data = $cfg->get($rank);

		}

		return $data;

	}

	

	public static function saveData(string $rank, array $data){

		$cfg = Main::getInstance()->newConfig("ranks");

		$cfg->set($rank, $data);

		$cfg->save();

	}

	

	public static function addPermission(string $rank, string $permission){

		$data = self::getData($rank);

		if($data == null) return false;

		$perms = $data["permissions"];

		$perms[] = $permission;

		$data["permissions"] = $perms;

		self::saveData($rank, $data);

		self::updatePermsToAll($rank, $perms);

		return true;

	}

		

	public static function create(string $namerank, string $color = null, array $permissions = []){

		$cfg = Main::getInstance()->newConfig("ranks");

		$data = [

		"permissions" => $permissions,

		"color" => $color,

		"chatFormat" => self::getChatFormat(),

		"tag" => self::getTagFormat(),

		];

		$cfg->set($namerank, $data);

		$cfg->save();

	}

	

	public static function updatePermsToAll(string $rank, array $perms){

		foreach(Server::getInstance()->getOnlinePlayers() as $p){

			if(self::userRank($p) == $rank){

				//self::updatePermsToPlayer($p, $perms);

				$at = Main::getInstance()->getAttachment($p);

				$at->clearPermissions();

				foreach($perms as $pe){

					$at->setPermission($pe, true);

				}

			}

		}

	}

	

	public static function updatePermsToPlayer($p, array $perms){

		$at = Main::getInstance()->getAttachment($p);

		$at->clearPermissions();

		foreach($perms as $pe){

			//$p->sendMessage("§a› Aplicando permiso: §7".$pe);

			$at->setPermission($pe, true);

		}

		$p->sendMessage("§a› Permisos aplicados correctamente!");

	}

	

	public static function userRank(Player $player){

		$cfg = Main::getInstance()->newConfig("players");

		$rank = "user";
		if(!self::isExists($rank)){
			self::create($rank, "§7");
		}
		if($cfg->get("RankDefault/") != null){

			if(self::isExists($cfg->get("RankDefault/"))){

				$rank = $cfg->get("RankDefault/");

			}

		}

		if($cfg->get($player->getName()) != null){

			$rank = $cfg->get($player->getName());

		}
		return $rank;

	}

	

	public static function remove(string $namerank){

		$cfg = Main::getInstance()->newConfig("ranks");

		$cfg->remove($namerank);

		$cfg->save();

	}

	

	public static function set($player, string $rank){

		$cfg = Main::getInstance()->newConfig("players");

		$cfg->set($player->getName(), $rank);

		$cfg->save();

		$perms = self::getData($rank)["permissions"];

		if(is_array($perms)){

			if(count($perms) != 0){

				self::updatePermsToPlayer($player, $perms);

			}

		}

		if(!$player instanceof Player) return;

		$player->sendMessage("§aSe te ha dado el rango §7'{$rank}'");

		$player->setNameTag(str_replace(["{rank}", "{name}", "{color}"], [$rank, $player->getName(), self::getData($rank)["color"]], self::getData($rank)["tag"]));

	}

	

	public static function setDefault(string $rank){

		$cfg = Main::getInstance()->newConfig("ranks");

		$cfg->set("RankDefault/", $rank);

		$cfg->save();

	}

}