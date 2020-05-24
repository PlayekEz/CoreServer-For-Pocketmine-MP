<?php

namespace Playek\Core\commands;

use pocketmine\Player;

use pocketmine\command\CommandSender;

use Playek\Core\Main;
use Playek\Core\api\Particles;
use Playek\Core\api\Elo;
use Playek\Core\utils\UI;

class ParticlesCommand extends \pocketmine\command\PluginCommand {

    private $main;

    public function __construct(Main $main){
        parent::__construct("p", $main);
        $this->main = $main;
        $this->setDescription("Particles for PlayOver");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        
        if(!$sender instanceof Player){
            $sender->sendMessage("Usa este comando en el juego");
            return false;
        }
        if(!isset($args[0])){
            $sender->sendMessage("§cUsa: /p <particle> <name : opcional>");
            return false;
        }
        $player = null;
        if(isset($args[1])){
            if($this->main->getServer()->getPlayer($args[1]) instanceof Player){
                $player = $this->main->getServer()->getPlayer($args[1]);
            }
        }
        $api = new Particles($this->main);
        $p = null;
        if($args[0] == "ui"){
            UI::requestUi($sender, UI::ID_PARTICLES);
            return true;
        }
        if($args[0] == "ch"){
            $p = "CrownHear";
        }
        if($args[0] == "sb"){
            $p = "SonicBoom";
        }
        if($args[0] == "remove"){
            if($player != null){
                if($api->isParticlePlayer($player->getName())){ 
                    $api->remove($player->getName());
                    $sender->sendMessage("§b» §7Le has desactivado las particulas a §a{$player->getName()}");
                    $player->sendMessage("§b» §a{$sender->getName()} §7te ha desactivado tus particulas");
                }else{
                    $sender->sendMessage("§cNo se ha activado ninguna particula anteriormente el usuario!");
                }
            }else{
                if($api->isParticlePlayer($sender->getName())){ 
                    $api->remove($sender->getName());
                    $sender->sendMessage("§b» §7Te has desactivado todas las particulas");
                }else{
                    $sender->sendMessage("§cNo te has activado ninguna particula anteriormente!");
                } 
            }
            return true;
        }
        
        if($player != null){
            
            if($player->getName() == $sender->getName()){
                $sender->sendMessage("§cNo puedes referirte a ti mismo como otra persona, usa solo /p <particle>");
                return false;
            }
            $api->setParticle($player->getName(), $p);
            $sender->sendMessage("§b» §7Le has activado las particulas §e{$p} §7a §a{$player->getName()}");
            $player->sendMessage("§b» §a{$sender->getName()} §7te ha activado las particulas §e{$e}");
        }else{
            $elo = new Elo($sender);
            if($p == "CrownHear"){
                if(!$this->main->iAlreadyBuy($sender->getName(), "particle_ch")){
                    if($elo->buy(3800)){
                        $api->setParticle($sender->getName(), $p);
                        $this->main->addArticle($sender->getName(), "particle_ch");
                        $sender->sendMessage("§b» §7Felicidades has comprado las particulas §e{$p} §7por 3800 Coins");
                        return true;
                    }
                }else{
                    $api->setParticle($sender->getName(), $p);
                    $sender->sendMessage("§b» §7Te has activado las particulas §e{$p}");
                    return true;
                }
            }
            if($p == "SonicBoom"){
                if(!$this->main->iAlreadyBuy($sender->getName(), "particle_sb")){
                    if($elo->buy(1050)){
                        $api->setParticle($sender->getName(), $p);
                        $this->main->addArticle($sender->getName(), "particle_sb");
                        $sender->sendMessage("§b» §7Felicidades has comprado las particulas §e{$p} §7por 1050 Coins");
                        return true;
                    }
                }else{
                    $api->setParticle($sender->getName(), $p);
                    $sender->sendMessage("§b» §7Te has activado las particulas §e{$p}");
                    return true;
                }
            }
        }
        
        return true;
    }
}
?>