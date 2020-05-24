<?php

namespace Playek\Core\task;

use pocketmine\math\Vector3;

use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\WaterDripParticle;
use pocketmine\level\particle\SmokeParticle;

use pocketmine\Player;

use Playek\Core\Main;
use Playek\Core\api\Particles;

class ParticleTask extends \pocketmine\scheduler\Task {

    private $main;

    public function __construct(Main $main){
        $this->main = $main;
    }

    public function onRun(int $currentTick): void {
        $api = new Particles($this->main);
        if(count($api->getAll()) != 0){
            foreach($api->getAll() as $name => $particle){
                $player = $this->main->getServer()->getPlayer($name);
                if($player instanceof Player && $player->isOnline()){
                    if($particle == "CrownHear"){
                        $center = new Vector3($player->getX(), $player->getY()+0.8, $player->getZ());
                        $particle = new HeartParticle($center);
                        $time = 1;
                        $pi = 3.14159;
                        $time = $time+0.1/$pi;
                        for($i = 0; $i <= 2*$pi; $i+=$pi/8){
                            $x = $time*cos($i) + $center->x;
                            $y = exp(-0.1*$time)*sin($time) + $center->y;
                            $z = $time*sin($i) + $center->z;
                            $particle->setComponents($x, $y+0.8, $z);
                            $player->getLevel()->addParticle($particle);
                        }
                    }
                    if($particle == "SonicBoom"){
                        $center = new Vector3($player->getX(), $player->getY(), $player->getZ());
                        $particle = new AngryVillagerParticle($center);
                        $time = 1;
                        $pi = 3.14159;
                        $time = $time+0.1/$pi;
                        for($i = 0; $i <= 2*$pi; $i+=$pi/8){
                            $x = $time*cos($i) + $player->x;
                            $z = exp(-0.1*$time)*sin($time) + $player->z;
                            $y = $time*sin($i) + $player->y;
                            $particle->setComponents($x, $y, $z);
                            $player->getLevel()->addParticle($particle);
                        }
                    }
                }else{
                    $api->remove($name);
                }
            }
        }
    }
}
?>