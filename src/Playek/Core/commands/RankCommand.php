<?php



namespace Playek\Core\commands;



use pocketmine\Player;

use pocketmine\Server;



use pocketmine\command\CommandSender;

use Playek\Core\Main;

use Playek\Core\utils\Permissions;

use Playek\Core\api\Rank;



class RankCommand extends \pocketmine\command\PluginCommand {

	

	private $main, $prefix;

	

	public function __construct(Main $main){

		parent::__construct("rank", $main);

		$this->main = $main;

	}

	

	public function execute(CommandSender $sender, string $label, array $args): bool {

		

		if(!$sender->hasPermission(Permissions::RANK_CMD_USE)){

			$sender->sendMessage("§cNo tienes permisos para utilizar este comando");

			return false;

		}

		if(!isset($args[0])){

			$sender->sendMessage("§cUsa /rank help");

			return false;

		}

		switch($args[0]){

			case "create": //Argumentos: /rank create <name> <color>
			
			if(!isset($args[1])){

				$sender->sendMessage("§cIndica un nombre para el rango");

				return true;

			}

			if(!isset($args[2])){

				$sender->sendMessage("§cIndica un color para el rango");

				return true;

			}

			if(Rank::isExists($args[1])){

				$sender->sendMessage("§cEl rango ya ha sido creado antes");

				return false;

			}

			$rank = new Rank($this->main);

			$rank->create($args[1], $args[2], []);

			$sender->sendMessage("§eRango Creado Sastifactoriamente!");

			break;

			case "remove":

			if(!isset($args[1])){

				$sender->sendMessage("§cEspecifica un rango a eliminar");

				return false;

			}

			if(!Rank::isExists($args[1])){

				$sender->sendMessage("§cEl rango no existe");

				return false;

			}

			Rank::remove($args[1]);

			$sender->sendMessage("§aEl Rango ".$sender->getName()." se ha removido");

			break;

			case "set":

			if(!isset($args[1])){

				$sender->sendMessage("§cEspecifica primero un rango");

				return false;

			}

			if(!isset($args[2])){

				$sender->sendMessage("§cIndica el usuario  quien se le dara el rango");

				return false;

			}

			if(!Rank::isExists($args[1])){

				$sender->sendMessage("§cEl rango no existe!");

				return false;

			}

			$player = Server::getInstance()->getPlayer($args[2]);

			if(!$player instanceof Player){

				$player = Server::getInstance()->getOfflinePlayer($args[2]);

			}

			$sender->sendMessage("§aSe aplico el rango '{$args[1]}' al jugador §7{$player->getName()}");

			Rank::set($player, $args[1]);

			break;

			case "addp": //1 => rango //2 => permiso

			if(!isset($args[1])){

				$sender->sendMessage("§cEspecifica primero un rango en donde agregar el permiso");

				return false;

			}

			if(!isset($args[2])){

				$sender->sendMessage("§cIndica el permiso que se agregara al rango");

				return false;

			}

			if(!Rank::isExists($args[1])){

				$sender->sendMessage("§cEl rango no existe!");

				return false;

			}

			if(Rank::addPermission($args[1], $args[2])){

				$sender->sendMessage("§aPermiso ".$args[2]." agregado correctamente!");

			}else{

				$sender->sendMessage("§cAlgo ocurrio al agregar el permiso...!");

			}

			break;

			case "default":

			if(!isset($args[1])){

				$sender->sendMessage("§cEspecifica un rango para establecer por defecto");

				return false;

			}

			if(!Rank::isExists($args[1])){

				$sender->sendMessage("§cEl rango que ingresaste no existe!");

				return false;

			}

			Rank::setDefault($args[1]);

			$sender->sendMessage("§aSe ha establecido el rango '{$args[1]}' por defecto");

			break;

			case "setchatformat": //1 => Rango // 2 => Formato

			if(!isset($args[1])){

				$sender->sendMessage("§cEspecifica un rango para actualizar su formato de chat");

				return false;

			}

			if(!Rank::isExists($args[1])){

				$sender->sendMessage("§cEl rango que ingresaste no existe!");

				return false;

			}

			$rankI = $args[1];

			if(!isset($args[2])){

				$format = Rank::getChatFormat();

			}else{

				unset($args[0]);

				unset($args[1]);

				$format = implode(" ", $args);

			}

			Rank::setChatFormat($rankI, $format);

			$sender->sendMessage("§aSe ha actualizado el formato de chat a §7'{$format}'");

			break;
			
			case "setpermissions":
			
			if(!isset($args[1])){
				$sender->sendMessage("§cIndica un rango para actualizar sus permisos");
				return false;
			}
			switch($args[1]){
				case "overman": //Chatcolor, Fly
					$perms = [
					Permissions::CC_CMD_USE,
					Permissions::FLY_CMD_USE,
					"sw.permission.player.start.sw",
					"sw.permission.player.select.cage",
					"sw.permission.player.cage.bricks",
					"sw.permission.player.cage.diamond",
					"sw.permission.player.cage.emerald",
					"sw.permission.player.cage.bedrock",
					"sw.permission.player.menu.miniyt",
					"sw.permission.player.menu.youtuber",
					"sw.permission.player.menu.famous",
					"sw.permission.player.kit.woodentools",
					"sw.permission.player.kit.jumper",
					"sw.permission.player.kit.goldtools",
					"sw.permission.player.kit.speeder",
					"sw.permission.player.kit.archer",
					"sw.permission.player.kit.irontools",
					"sw.permission.player.kit.zeus",
					"sw.permission.player.kit.regeneration",
					"sw.permission.player.kit.diamondtools",
					"sw.permission.player.kit.regeneration2",
					"sw.permission.player.cage.bricks",
					"sw.permission.player.cage.diamond",
					"sw.permission.player.cage.emerald",
					"sw.permission.player.cage.bedrock",
					Permissions::ME_CMD_USE];
					foreach($perms as $perm){
						$this->main->getServer()->dispatchCommand($sender, "rank addp OverMan ".$perm);
					}
				break;
				case "staff":
					$perms = [
					Permissions::RANK_CMD_USE,
					Permissions::BAN_CMD_USE,
					Permissions::MUTE_CMD_USE,
					Permissions::FLY_CMD_USE,
					Permissions::ME_CMD_USE,
					"pocketmine.command.gamemode",
					"pocketmine.command.say",
					"pocketmine.command.tp",
					"pocketmine.command.teleport",
					"pocketmine.command.time",
					];
					foreach($perms as $perm){
						$this->main->getServer()->dispatchCommand($sender, "rank addp Staff ".$perm);
					}
				break;
				case "myt":
					$perms = [
					"sw.permission.player.menu.miniyt",
					"sw.permission.player.kit.woodentools",
					"sw.permission.player.kit.jumper",
					"sw.permission.player.select.cage",
					Permissions::CAPE_FIREWORK,
					"sw.permission.player.cage.bricks"];
					foreach($perms as $perm){
						$this->main->getServer()->dispatchCommand($sender, "rank addp MiniYT ".$perm);
					}
				break;
				case "yt":
					$perms = [
					"sw.permission.player.start.sw",
					"sw.permission.player.select.cage",
					"sw.permission.player.cage.diamond",
					"sw.permission.player.cage.bricks",
					"sw.permission.player.menu.youtuber",
					"sw.permission.player.kit.goldtools",
					"sw.permission.player.kit.speeder",
					"sw.permission.player.kit.archer",
					Permissions::CC_CMD_USE,
					Permissions::CAPE_FIREWORK,
					Permissions::FLY_CMD_USE,
					Permissions::CAPE_ENERGY];
					foreach($perms as $perm){
						$this->main->getServer()->dispatchCommand($sender, "rank addp Youtuber ".$perm);
					}
				break;
				case "famous":
					$perms = [ 
					"sw.permission.player.start.sw",
					"sw.permission.player.select.cage",
					"sw.permission.player.cage.bricks",
					"sw.permission.player.cage.diamond",
					"sw.permission.player.cage.emerald",
					Permissions::FLY_CMD_USE,
					Permissions::CC_CMD_USE,
					Permissions::ME_CMD_USE,
					Permissions::CAPE_FIREWORK,
					Permissions::CAPE_ENERGY,
					Permissions::CAPE_BLUECREEPER];
					foreach($perms as $perm){
						$this->main->getServer()->dispatchCommand($sender, "rank addp Famous ".$perm);
					}
				break;
				case "cacahuate":
					$perms = [
					Permissions::START_SW,
					Permissions::CC_CMD_USE,
					Permissions::CAPE_ENERGY];
					foreach($perms as $perm){
						$this->main->getServer()->dispatchCommand($sender, "rank addp Cacahuate ".$perm);
					}
				break;
			}
			break;
		}
		return false;
	}

}