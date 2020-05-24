<?php

namespace Playek\Core\commands;

use pocketmine\Player;

use pocketmine\command\{CommandSender, PluginCommand};

use Playek\Core\Main;
use Playek\Core\utils\Permissions;

class FlyCommand extends PluginCommand {

    private $main;

    public function __construct(Main $main){
        parent::__construct("fly", $main);
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if(!$sender instanceof Player){
            $sender->sendMessage("§cUsa este comando en el juego");
            return false;
        }
        if (!$sender->hasPermission(Permissions::FLY_CMD_USE)) {
            $sender->sendMessage("§cNo tienes permisos para utilizar este comando");
            return false;
        }
        if($sender->getAllowFlight()){
            $sender->setAllowFlight(false);
            $sender->setFlying(false);
            $sender->sendMessage("§aTu modo de vuelo ha sido desactivado");
        }else{
            $sender->setAllowFlight(true);
            $sender->setFlying(true);
            $sender->sendMessage("§aTu modo de vuelo ha sido activado");
        }
        return false;
    }
}