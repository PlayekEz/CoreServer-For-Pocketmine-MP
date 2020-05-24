<?php

namespace Playek\Core\task;

use pocketmine\Player;

use Playek\Core\Main;
use Playek\Core\api\Rank;
use Playek\Core\api\Elo;
use Playek\Core\utils\Utils;

use Scoreboards\Scoreboards;

class Scoreboard extends \pocketmine\scheduler\Task {
	
	private $main;
	private $count = -1;
	public function __construct(Main $main){
		$this->main = $main;
	}
	
	public function onRun($currentTick) {
		
		$lines1 = [
			"§aPLAY§eOVER",
			"§aPLAY§eOVER",
			"§fP§aLAY§aOVE§fR",
			"§fPL§aAY§eOV§fER",
			"§fPLA§aY§eO§fVER",
			"§fPLA§aY§eO§fVER",
			"§fPLAYOVER",
			"§fPLAYOVER",
			"§aPLAYOVER",
			"§aPLAYOVER",
			"§ePLAYOVER",
			"§ePLAYOVER",
			"§aPLAY§eOVER",
			"§aPLAY§eOVER",
		];
		$this->count++;
		if($this->count == count($lines1)){
			$this->count = 0;
		}

		foreach($this->main->getServer()->getOnlinePlayers() as $p){
			if($p instanceof Player && $p->isOnline()){
				if($p->getLevel()->getFolderName() == Utils::getHubLevelToString()){
					$api = Scoreboards::getInstance();
					$api->new($p, $p->getName(), "§l".$lines1[$this->count]);
					$i = 0;
					$lines = [
						"        ",
						"§6» ".$p->getName(),
						"               ",
						"Rango: §l".(Rank::getData(Rank::userRank($p))["color"]).strtoupper(Rank::userRank($p)),
						"Monedas: §a".(new Elo($p))->get(),
						"   ",
						"Conectados: §a".count($this->main->getServer()->getOnlinePlayers()),
						"      ",
						"§e".Main::IP_SERVER." ".Main::PORT
					];
					$rank = Rank::userRank($p);
					$p->setNameTag(str_replace(["{rank}", "{name}", "{color}"], [$rank, $p->getName(), Rank::getData($rank)["color"]], Rank::getData($rank)["tag"])."\n"."§e(".$this->main->getPlayerOs($p).")");
					foreach($lines as $line){
						if($i < 15){
							$i++;
							$api->setLine($p, $i, $line);
						}
					}
				}
			}
		}
	}
}