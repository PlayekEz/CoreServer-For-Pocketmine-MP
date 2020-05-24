<?php

namespace Playek\Core\commands;

use pocketmine\command\{CommandSender, PluginCommand, ConsoleCommandSender};

use pocketmine\Player;

use Playek\Core\Main;
use Playek\Core\api\Rank;
class ScCommand extends PluginCommand {

    public function __construct(Main $main){
        parent::__construct("sc", $main);
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        $name = $sender->getName();
        $rank = new Rank($this->main);
        $message = implode(" ", $args);
        $prefix = "§5§l[sᴄ] §e{$name} §8› §7{$message}";
        foreach($rank->getPlayers("staff", true) as $p){
            if($p instanceof Player){
                $p->sendMessage($prefix);
            }
        }
        $this->main->getLogger()->info($prefix);
        return false;
    }
}