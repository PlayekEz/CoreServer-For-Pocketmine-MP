<?php

namespace Playek\Core\task;

use pocketmine\Player;

class FreezeExect extends \pocketmine\scheduler\Task {
	
	private $main;
	private $prefix;
	
	public function __construct(Main $main){
		$this->main = $main;
		$this->prefix = $main->prefix;
	}
	
	public function onRun(int $currentTick): void {
		$cfg = $this->main->newConfig("freezedDat");
		foreach($this->main->getServer()->getOnlinePlayers() as $p){
			if($cfg->get($p->getName()) != null){
				$data = $cfg->get($p->getName());
				$type = "Permanente";
				if(isset($data["TYPE"])){
					$type = $data["TYPE"];
				}
				switch($type){
					case "Permanente":
					$p->addTitle("§l