<?php

namespace Playek\Core\utils;

use pocketmine\Player;

use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;

use Playek\Core\utils\Permissions;
use Playek\Core\Main;

class UI {

	const ID_MINIGAMES = 20;
	const ID_INFORMATION = 21;
	
	const ID_TB_PLAYAGAIN = 22;
	const ID_SW_PLAYAGAIN = 23;
	
	const ID_MENU_CAPES = 30;
	const ID_CAPES_EXCLUSIVES = 31;
	const ID_CAPES_FREE = 32;
	const ID_CAPES_BUY = 34;
	
	const ID_COSMETICS = 33;
	const ID_PARTICLES = 35;

    private $main;

    public function __construct(Main $main){
        $this->main = $main;
    }

	public static function requestUI(Player $p, int $id){
		if($id == null) return;
		switch($id){
			case self::ID_MINIGAMES:
				$data = self::minigames($p);
			break;
			case self::ID_INFORMATION:
				$data = self::information($p);
			break;
			case self::ID_SW_PLAYAGAIN:
				$data = self::playAgainSw($p);
			break;
			case self::ID_TB_PLAYAGAIN:
				$data = self::playAgainTb($p);
			break;
			case self::ID_MENU_CAPES:
				$data = self::menuCapes($p);
			break;
			case self::ID_CAPES_EXCLUSIVES:
				$data = self::capesExclusive($p);
			break;
			case self::ID_COSMETICS:
				$data = self::cosmetics($p);
			break;
			case self::ID_CAPES_FREE:
				$data = self::capesFree($p);
			break;
			case self::ID_CAPES_BUY:
				$data = self::capesBuy($p);
			break;
			case self::ID_PARTICLES:
				$data = self::particles($p);
			break;
			default:
			
				$data = [];
				return;
			break;
		}
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = $data;
		$p->dataPacket($pk);
	}

	public static function capesFree(Player $player) {
		$ig["text"] = "Iron Golem"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		$pickaxe["text"] = "Pickaxe"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		$yt["text"] = "Youtube"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		$data = [
		"type" => "form",
		"title" => "§l§bCAPAS",
		"content" => "§7Escoje una de las capas gratis 7w7",
		"buttons" => []];
		foreach([$ig, $pickaxe, $yt] as $button){
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}

	public static function capesBuy(Player $player){
		$turtle["text"] = "Turtle"."\n"."§o§e520 Coins";
		if(Main::getInstance()->iAlreadyBuy($player->getName(), "cape_turtle")){
			$turtle["text"] = "Turtle"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
			
		}
		$rc["text"] = "Red Creeper"."\n"."§o§730 Coins";
		if(Main::getInstance()->iAlreadyBuy($player->getName(), "cape_rc")){
			$rc["text"] = "Red Creeper"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		}

		$data = [
		"type" => "form",
		"title" => "§l§bCAPAS",
		"content" => "§7Lista de capas, si no te alcanza para una. Juega Mas Minijuegos!",
		"buttons" => []];
		
		foreach ([$turtle, $rc] as $button) {
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}

	
	public static function playAgainTb(Player $p){
		$play["text"] = "§l§a¿Jugar De Nuevo?";
		$spect["text"] = "§e§l¡Espectador!";
		$exit["text"] = "§l§c¿Regresar Al Lobby?";
		
		$data = [
		"type" => "form",
		"title" => "§l§9THE BRIDGE",
		"content" => "",
		"buttons" => []];
		
		foreach([$play, $exit, $spect] as $button){
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}
	
	public static function particles(Player $player){
		$ch["text"] = "§fCrown Hear"."\n"."§o§e3800 Coins";
		if(Main::getInstance()->iAlreadyBuy($player->getName(), "particle_ch")){
			$ch["text"] = "§fCrown Hear"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		}
		$sb["text"] = "§fSonic Boom"."\n"."§o§e1050 Coins";
		if(Main::getInstance()->iAlreadyBuy($player->getName(), "particle_sb")){
			$sb["text"] = "§fSonic Boom"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		}
		$de["text"] = "§l§4DESACTIVAR";
		$data = [
		"type" => "form",
		"title" => "§l§ePARTICULAS",
		"content" => "",
		"buttons" => []];
			
		foreach([$ch, $sb, $de] as $button){
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}
	public static function playAgainSw(Player $p){
		$play["text"] = "§l§a¿Jugar De Nuevo?";
		$spect["text"] = "§e§l¡Espectador!";
		$exit["text"] = "§l§c¿Regresar Al Lobby?";
		
		$data = [
		"type" => "form",
		"title" => "§l§6SKYWARS",
		"content" => "",
		"buttons" => []];
		
		foreach([$play, $exit, $spect] as $button){
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}
	
    public static function minigames(Player $p){
		$sw["text"] = "§l§6SKYWARS"."\n"."§r§aDisponible!";
		$tb["text"] = "§l§9THE BRIDGE"."\n"."§r§aDisponible!";
		$ffa["text"] = "§l§2FREE FOR ALL"."\n"."§r§aDisponible!";
		$data = [
		"type" => "form",
		"title" => "§l§eMINIJUEGOS",
		"content" => "§7Selecciona un minijuego disponible!",
		"buttons" => []];
		foreach([$sw, $tb, $ffa] as $button){
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}

	public static function information(Player $p){
		$close["text"] = "§l§4ᴄᴇʀʀᴀʀ";

		$data = [
		"type" => "form",
		"title" => "§l§aINFORMACION",
		"content" => 
		"§7§lᴠolvemos con mas mejoras!"."\n\n".
		"§7§lʜemos implementado una funcion para poder tener mas partidas en los minijuegos sin importar los límites de mapas!"."\n\n".
		"§7§lse implementaran mas modalidades proximamente en el servidor, se espera:"."\n\n".
		"§c§lᴡeaponwar §r§e§k|§r §l§cᴛntrun §r§e§k|§r §l§eʟucky§6ᴡars",
		"buttons" => []];
		$data["buttons"][] = $close;
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}
	
	public static function cosmetics(Player $player){
		$capes["text"] = "§l§bCAPAS";
		$fly["text"] = "§l§aVUELO";
		$cc["text"] = "§l§eC§aH§fA§3T§6 C§dO§cL§bO§9R";
		$particles["text"] = "§l§ePARTICULAS";
		
		$data = [
		"type" => "form",
		"content" => "",
		"title" => "§c§lCOSMETICOS",
		"buttons" => []];
		foreach([$capes, $fly, $cc, $particles] as $button){
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}
	
	public static function menuCapes(Player $player){
		$exclusive["text"] = "§bExclusivas";
		$free["text"] = "§aGratis";
		$buy["text"] = "§eEn Venta";
		
		$data = [
		"type" => "form",
		"title" => "§l§bCAPAS",
		"content" => "",
		"buttons" => []];
		foreach([$exclusive, $free, $buy] as $button){
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}
	
	public static function capesExclusive(Player $player){
		if($player->hasPermission(Permissions::CAPE_BLUECREEPER)){
			$bc["text"] = "§l§b•§fʙʟᴜᴇ ᴄʀᴇᴇᴘᴇʀ§b•§r"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		}else{
			$bc["text"] = "§l§b•§fʙʟᴜᴇ ᴄʀᴇᴇᴘᴇʀ§b•§r"."\n"."§c§lɴᴏ ᴅɪsᴘᴏɴɪʙʟᴇ";
		}
		if($player->hasPermission(Permissions::CAPE_ENERGY)){
			$energy["text"] = "§l§b•§fᴇɴᴇʀɢʏ§b•§r"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		}else{
			$energy["text"] = "§l§b•§fᴇɴᴇʀɢʏ§b•§r"."\n"."§c§lɴᴏ ᴅɪsᴘᴏɴɪʙʟᴇ";
		}
		if($player->hasPermission(Permissions::CAPE_FIREWORK)){
			$firework["text"] = "§l§b•§fғɪʀᴇᴡᴏʀᴋ§b•§r"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		}else{
			$firework["text"] = "§l§b•§fғɪʀᴇᴡᴏʀᴋ§b•§r"."\n"."§c§lɴᴏ ᴅɪsᴘᴏɴɪʙʟᴇ";
		}
		if($player->hasPermission(Permissions::CAPE_FIRE)){
			$fire["text"] = "§l§b•§fғɪʀᴇ§b•§r"."\n"."§a§lᴅɪsᴘᴏɴɪʙʟᴇ";
		}else{
			$fire["text"] = "§l§b•§fғɪʀᴇ§b•§r"."\n"."§c§lɴᴏ ᴅɪsᴘᴏɴɪʙʟᴇ";
		}
		$data = [
		"type" => "form",
		"title" => "§l§bCAPAS",
		"content" => "",
		"buttons" => []];
		foreach([$bc, $energy, $firework, $fire] as $button){
			$data["buttons"][] = $button;
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
	}
}