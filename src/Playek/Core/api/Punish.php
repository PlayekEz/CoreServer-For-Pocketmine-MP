<?php



namespace Playek\Core\api;



use pocketmine\Player;

use pocketmine\Server;



use pocketmine\utils\TextFormat as T;



use Playek\Core\Main;



class Punish {

	

	private $main;

	

	public function __construct(Main $main){

		$this->main = $main;

	}

	

	public static function setTime(string $name, int $time){

		$data = self::getData($name);

		if($data == null) return;

		$time1 = $data["TIME"];

		if($time1 == "~") return;
		
		if($time1 == null) return;

		$data["TIME"] = $time;

		self::saveData($name, $data);

	}

	public static function getData(string $name){

		$cfg = Main::getInstance()->newConfig("punish");

		$data = null;

		if($cfg->get($name) != null){

			$data = $cfg->get($name);

		}

		return $data;

	}

	

	public static function isBanned($player){

		$data = self::getData(strtolower($player));

		$bool = false;

		if($data == null){

			$bool = false;

		}

		if(isset($data['PUNISH'])){

			if($data["PUNISH"] == "BAN"){

				$bool = true;

			}

		}

		return $bool;

	}

	

	public static function isMuted($player){

		$data = self::getData(strtolower($player));

		$bool = false;

		if($data == null){

			$bool = false;

		}

		if(isset($data['PUNISH'])){

			if($data["PUNISH"] == "MUTE"){

				$bool = true;

			}

		}

		return $bool;

	}

	

	public static function saveData(string $name, array $data){

		$cfg = Main::getInstance()->newConfig("punish");

		$cfg->set(strtolower($name), $data);

		$cfg->save();

	}

	

	public static function register($name, $punish, $type = "P", $rason = "", $time = null){
		$tw = 0;
		if(!$time == 0 or !$time == null){
			$tw = $time * 64;
		}
		$data = [

		"NAME" => strtolower($name),

		"PUNISH" => $punish, //Ban or Mute

		"TYPE" => $type, //Permanent or Temp

		"TIME" => (int) $tw, //Null or time

		"REASON" => $rason,

		];

		if($punish == "BAN"){

			if(!Server::getInstance()->getPlayer($name) instanceof Player) return;

			self::sendBan(Server::getInstance()->getPlayer($name), $rason);
			self::saveData($name, $data);
			return;

		}
		self::saveData(strtolower($name), $data);
		return;

	}

	

	public static function cancel($player){

		self::remove($player);

	}

	

	public static function sendBan(Player $player, string $reason, int $time = 0){

		$player->close("", "§c¡Has Sido Baneado De PlayOver!"."\n\n"."§7§o".$reason);
		$name = $player->getName();
		if($time != null){
			Server::getInstance()->broadcastMessage("§c{$name}§7 ha sido baneado durante {$time} §7minutos por §4{$reason}");
			return;
		}
		Server::getInstance()->broadcastMessage("§c{$name}§7 ha sido baneado permanentemente por §4{$reason}");
		return;
	}

	

	public static function remove(string $name){

		$cfg = Main::getInstance()->newConfig("punish");

		$cfg->remove($name);

		$cfg->save();

	}

	

	public static function getPlayers(){

		$cfg = Main::getInstance()->newConfig("punish");

		$players = [];

		foreach($cfg->getAll() as $k => $v){

			$players[$k] = $v;

		}

		return $players;

	}

}