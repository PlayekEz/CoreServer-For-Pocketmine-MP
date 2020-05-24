<?php

namespace Playek\Core\commands;

use pocketmine\command\{CommandSender, PluginCommand};

use pocketmine\Player;
use pocketmine\Server;

use Playek\Core\Main;
use Playek\Core\utils\Permissions;
use Playek\Core\api\Punish;

class MuteCommand extends PluginCommand {

    private $main;

    public function __construct(Main $main){
        parent::__construct("mute", $main);
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        /*
        if(!$sender instanceof Player){
            
        }
        */
        $server = $this->main->getServer();
        if(!$sender->hasPermission(Permissions::MUTE_CMD_USE)){
            $sender->sendMessage("§cNo tienes permisos para utilizar este comando");
            return false;
        }
        if(!isset($args[0])){
            $sender->sendMessage("§cUsa: /mute <player> <time> <reason>");
            return false;
        }
        if($args[0] == "cancel"){
            if(!isset($args[1])){
                $sender->sendMessage("§cEspecifica un usuario a desmutear");
                return false;
            }
            $name = $args[1];
            if($this->main->getServer()->getPlayer($args[1]) instanceof Player){
                $name = $this->main->getServer()->getPlayer($args[1])->getName();
            }
            if(!Punish::isMuted($name)){
                $sender->sendMessage("§7{$name} no ha sido muteado..!");
                return false;
            }
            Punish::cancel(strtolower($name));
            $sender->sendMessage("§aHas desmuteado correctamente a §7{$name}");
            if($this->main->getServer()->getPlayer($name) instanceof Player){
                $this->main->getServer()->getPlayer($name)->sendMessage("§aFelicidades, has sido desmuteado");
            }
            
            return true;
        }else{ // /mute <player> <time> <reason>
            if(!isset($args[0])){
                $sender->sendMessage("§cEspecifica un usuario a mutear");
                return false;
            }
            $pl = $this->main->getServer()->getPlayer($args[0]);
            if(!$pl instanceof Player){
                $sender->sendMessage("§cEl usuario {$args[0]} no esta en linea");
                return false;
            }
            if(!isset($args[1])){
                $sender->sendMessage("§cEstablece un tiempo para el muteo de §7{$pl->getName()}, §aUsa /mute timehelp");
                return false;
            }
            $time = $args[1];
            if(!is_numeric($time)){
                $sender->sendMessage("§cTiempo imvalido, intenta poner balores numericos");
                return false;
            }
            $reason = "";
            if(isset($args[2])){
                unset($args[0]);
                unset($args[1]);
                $reason = implode(" ", $args);
            }
            if($time != 0){
                Punish::register($pl->getName(), "MUTE", "T", $reason, $time);
                if($reason == ""){
                    $pl->sendMessage("§cHas sido muteado §7{$time} minutos §cpor §a{$sender->getName()}");
                    $sender->sendMessage("§aHas muteado a §c{$pl->getName()} §adurante §7{$time} minutos");
                }else{
                    $pl->sendMessage("§cHas sido muteado §7{$time} minutos §cpor §a{$sender->getName()} §cdebido a §o§7{$reason}");
                    $sender->sendMessage("§aHas muteado a §c{$pl->getName()} §adurante §7{$time} minutos §adebido a §o§7{$reason}");
                }
                return true;
            }else if ($time == 0){
                Punish::register($pl->getName(), "MUTE", "P", $reason, 0);
                if($reason != ""){
                    $pl->sendMessage("§cHas sido muteado §7permanentemente §cpor §a{$sender->getName()} §cdebido a §o§7{$reason}");
                    $sender->sendMessage("§aHas muteado a §c{$pl->getName()} §cpermanentemente §adebido a §o§7{$reason}");
                }else{
                    $pl->sendMessage("§cHas sido muteado §7permanentemente §cpor §a{$sender->getName()}");
                    $sender->sendMessage("§aHas muteado a §c{$pl->getName()} §cpermanentemente");
                }
                return true;
            }
            $pols = [];
            foreach($server->getOnlinePlayers() as $pol){
                $pols[$pol->getName()] = $pol;
            }
            if(isset($pols[$pl->getName()])){
                unset($pols[$pl->getName()]);
            }
            foreach($pols as $k => $v){
                if($v instanceof Player){
                    $v->sendMessage("§c{$pl->getName()} §7ha sido muteado.");
                }
            }
            
        }
        return true;
    }
}