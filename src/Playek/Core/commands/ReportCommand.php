<?php



namespace Playek\Core\commands;



use pocketmine\Player;

use pocketmine\Server;



use pocketmine\command\CommandSender;

use Playek\Core\Main;

use Playek\Core\utils\Permissions;

use Playek\Core\api\Rank;



class ReportCommand extends \pocketmine\command\PluginCommand {

	

	private $main, $prefix;

	

	public function __construct(Main $main){

		parent::__construct("report", $main);

		$this->main = $main;

	}

	

	public function execute(CommandSender $sender, string $label, array $args): bool {
        $server = Server::getInstance();
        $rankapi = new Rank($this->main);
		if(!$sender instanceof Player){

			$sender->sendMessage("Usa en el juego");

			return false;

        }

		if(!isset($args[0])){

			$sender->sendMessage("§cUsa /report <jugador> <motivo>");

			return false;

		}

		if($args[0] == "checklist"){
            if(!$sender->hasPermission(Permissions::RANK_CMD_USE)){
                return false;
            }
            if(!isset($args[1])){
                $sender->sendMessage("§cEspecifica un usuario para ver sus reportes realizados en esta sesion");
                return false;
            }
            $name = $args[1];
            if($server->getPlayer($name) instanceof Player){
                $name = $server->getPlayer($name)->getName();
            }
            if(!isset($this->main->reports[$name])){
                $sender->sendMessage("§cEl usuario '{$name}' no ha reportado nada");
                return false;
            }
            $sender->sendMessage("§aMostrando reportes {$name} 0 - ".count($this->main->reports[$name]));
            foreach ($this->main->reports[$name] as $key => $value) {
                $sender->sendMessage("§8<------------------------------");
                $sender->sendMessage("§7Usuario: §b".$key);
                $sender->sendMessage("§7Motivo: §b".$value["Motivo"]);
                $sender->sendMessage("§7Remitente: §b".$value["Remitente"]);
                $sender->sendMessage("§8------------------------------>");
            }
        }else{
            if(!isset($args[1])){
                $sender->sendMessage("§cUsa /report <jugador> <motivo>");

			    return false;
            }
            $p = null;
            if($server->getPlayer($args[0]) instanceof Player){
                $p = $server->getPlayer($args[0]);
            }
            if($p == null){
                $sender->sendMessage("§cEl usuario {$args[0]} no esta en linea");
                return false;
            }
            unset($args[0]);
            $motivo = implode(" ", $args);
            foreach ($rankapi->getPlayers("staff", true, Permissions::RANK_CMD_USE) as $k => $v) {
                if($v instanceof Player){
                    $v->addTitle("§l§cALERTA", "§l§6HA LLEGADO UN REPORTE", 5, 20, 5);
                    $v->sendMessage("§8<------------------------------");
                    $v->sendMessage("§6Nuevo reporte, detalles:");
                    $v->sendMessage($this->prefix."§r§7Usuario: §b{$p->getName()}");
                    $v->sendMessage($this->prefix."§r§7Motivo: §b{$motivo}");
                    $v->sendMessage($this->prefix."§r§7Remitente: §b{$sender->getName()}");
                    $v->sendMessage("§8------------------------------>");
                }
            }
            $this->main->reports[$sender->getName()][$p->getName()] = [
                "Motivo" => $motivo,
                "Remitente" => $sender->getName(),
            ];
            
        }
        return false;
    }
}



