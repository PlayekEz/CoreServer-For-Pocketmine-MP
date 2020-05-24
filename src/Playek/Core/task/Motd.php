<?php

namespace Playek\Core\task;

use Playek\Core\Main;

class Motd extends \pocketmine\scheduler\Task {

    private $main;

    public function __construct(Main $main){
        $this->main = $main;
    }

    public function onRun(int $currentTick): void {
        $msg = [   
            "§7Aprovecha la cuarentena y entra con nosotros 7w7",
            "§eづ￣ 3￣)づ §l§aPLAY§eOVER §86.0",
            "§7Proximamente mas minijuegos! :D",
            "§7Siguenos via twitter §b@SoyPlayek",
            "§e@SoyPlayek - @SrDxni - @kmikzeFF"
        ];
        $output = $msg[array_rand($msg)];
        $this->main->getServer()->getNetwork()->setName($output);
        return;
    }
 }
 ?>