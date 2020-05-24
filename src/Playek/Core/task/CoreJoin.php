<?php

namespace Playek\Core\task;

use pocketmine\Player;
use Playek\Core\utils\Utils;
use Playek\Core\Main;
use Playek\Core\api\Rank;

class CoreJoin extends \pocketmine\scheduler\Task {

    private $main;
    private $player;
    private $time = 0;
    public function __construct(Player $player){
        $this->player = $player;
        $this->main = Main::getInstance();
    }

    public function onRun(int $currentTick): void {
        $p = $this->player;
        if(!$p instanceof Player){
            $this->cancel();
        }
        if($p instanceof Player && $p->isOnline()){
            $this->time++;
            if($this->time == 2){
                $p->getInventory()->clearAll();
                $p->getArmorInventory()->clearAll();
                $p->removeAllEffects();
                $p->setGamemode(2);
                $p->setMaxHealth(20);
                $p->setHealth(20);
                $p->setFood(20); 
                $p->teleport(Utils::getHubLevel()->getSpawnLocation(),0,0);
                $this->main->playSound($p, "mob.elderguardian.curse");
                Utils::gadgetsLobby($p); 
                return;
            }
            if($this->time == 3){
                $this->cancel();
                return;
            }
        }
    }

    public function cancel(): void {
        $this->main->getScheduler()->cancelTask($this->getTaskID());
        return;
    }
}