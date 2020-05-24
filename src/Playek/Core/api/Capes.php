<?php

namespace Playek\Core\api;

use pocketmine\Player;
use pocketmine\entity\Skin;

use Playek\Core\Main;

class Capes {
	
	
	public function __construct(Main $main){
		$this->main = $main;
	}
	
	public function createCape($capeName){
		$path = $this->main->getDataFolder() . "capes/{$capeName}.png";
		$img = @imagecreatefrompng($path);
		$bytes = '';
		$l = (int)@getimagesize($path)[1];
		for ($y = 0; $y < $l; $y++) {
			for ($x = 0; $x < 64; $x++) {
				$rgba = @imagecolorat($img, $x, $y);
				$a = ((~((int)($rgba >> 24))) << 1) & 0xff;
				$r = ($rgba >> 16) & 0xff;
				$g = ($rgba >> 8) & 0xff;
				$b = $rgba & 0xff;
				$bytes .= chr($r) . chr($g) . chr($b) . chr($a);
			}
		}
		@imagedestroy($img);
		return $bytes;
	}
	
	public function setCape(Player $player, string $capeName): bool {
		if(!file_exists($this->main->getDataFolder() . "capes/" . $capeName . ".png")){
			$player->sendMessage("§cLa capa seleccionada no existe en el servidor!");
			return false;
		}
		$oldSkin = $player->getSkin();
		$capeData = $this->createCape($capeName);
		$skin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
		$player->setSkin($skin);
		$player->sendSkin();
		$player->sendMessage("§b» §7Te has colocado la capa §b{$capeName}");
		$this->main->capePlayer[$player->getName()] = $capeName;
		return true;
	}

	
}
?>