<?php

namespace Playek\Core\api;

use pocketmine\Player;

use Playek\Core\Main;

class Elo {
	
	private static $player;
	private static $instance;
	
	public function __construct(Player $player){
		self::$player = $player;
		self::$instance;
	}
	
	public static function getInstance(): Elo {
		return self::$instance;
	}
	
	public function buy(int $price): bool {
		$coins = $this->get();
		if($coins <= 0){
			self::$player->sendMessage("§cLo sentimos, no cuentas con monedas suficientes para esta compra");
			return false;
		}
		if(($price) > ($coins)){
			self::$player->sendMessage("§cLo sentimos, no cuentas con monedas suficientes para esta compra");
			return false;
		}
		$this->substract($price);
		return true;
	}
	
	public static function getData(){
		$cfg = Main::getInstance()->newConfig("coins");
		if($cfg->get(self::$player->getName()) == null){
			$cfg->set(self::$player->getName(), 0);
			$cfg->save();
		}
		return $cfg->get(self::$player->getName());
	}
	
	public function add(int $elo){
		$eloa = self::getData();
		self::set($eloa + $elo);
	}
	
	public function substract(int $elo){
		$eloa = self::getData();
		self::set($eloa - $elo);
	}
	
	public function set($data){
		$cfg = Main::getInstance()->newConfig("coins");
		$cfg->set(self::$player->getName(), $data);
		$cfg->save();
	}
	
	public function get(){
		$data = self::getData();
		return $data;
	}
}