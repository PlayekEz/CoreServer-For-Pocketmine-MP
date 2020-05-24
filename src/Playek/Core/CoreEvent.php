<?php



namespace Playek\Core;



use pocketmine\event\Listener;



use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\event\player\PlayerPreLoginEvent;

use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\event\player\PlayerChatEvent;

use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\event\player\PlayerExhaustEvent;

use pocketmine\event\player\PlayerCommandPreprocessEvent;

use pocketmine\event\entity\EntityDamageEvent;

use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;



use pocketmine\Player;

use pocketmine\Server;

use pocketmine\plugin\Plugin;

use Playek\Core\utils\Utils;

use Playek\Core\utils\Permissions;

use Playek\Core\utils\UI;

use Playek\Core\Main;

use Playek\Core\api\Rank;

use Playek\Core\api\Punish;

use Playek\Core\task\CoreJoin;

use Playek\Sw\SWArena;



class CoreEvent implements Listener {

	

	private $main;

	

	public function __construct(Main $main){
		$this->main = $main;

	}


	public function onPreLogin(PlayerPreLoginEvent $ev){

		$p = $ev->getPlayer();

		

		if(Punish::isBanned($p->getName())){

			$ev->setCancelled();

			$p->close("", "Sigues Baneado En PlayOver"."\n\n"."Contactanos Por Twitter §b@PlayOver11");

		}

	}

	public function onInteract(PlayerInteractEvent $ev){
		$p = $ev->getPlayer();
		$item = $p->getInventory()->getItemInHand();
		if($p->getGamemode() == 0 || $p->getGamemode() == 2){
			switch($item->getName()){
				case Utils::G_L1:
					UI::requestUI($p, UI::ID_INFORMATION);
				break;
				case Utils::G_L2:
					UI::requestUI($p, UI::ID_MINIGAMES);
				break;
				case Utils::G_L3:
					UI::requestUI($p, UI::ID_COSMETICS);
				break;
			}
		}
	}

	public function onRecive(DataPacketReceiveEvent $ev){
		$p = $ev->getPlayer();
		if($ev->getPacket() instanceof ModalFormResponsePacket){
			$data = json_decode($ev->getPacket()->formData, true);
			
			switch($ev->getPacket()->formId){
				case UI::ID_MINIGAMES:
					if($data == null && $data !== 0) return;

					if($data == 0){
						$sw = $this->main->getServer()->getPluginManager()->getPlugin("SkyWars");
						if(!$sw instanceof Plugin) return false;
						$sw->exectJoin($p, true);
					}
					if($data == 1){
						$tb = $this->main->getServer()->getPluginManager()->getPlugin("TheBridge");
						if(!$tb instanceof Plugin) return false;
						$tb->exectJoin($p, false);
					}
					if($data == 2){
						$ffa = $this->main->getServer()->getPluginManager()->getPlugin("FFA");
						if(!$ffa instanceof Plugin) return false;
						$ffa->join($p, false);
					}
				break;
				case UI::ID_SW_PLAYAGAIN:
					if($data == null && $data !== 0) return;
					
					if($data == 0){
						$plugin = $this->main->getServer()->getPluginManager()->getPlugin("SkyWars");
						if(!$plugin instanceof Plugin) return;
						$plugin->playAgain($p);
						return;
					}
					if($data == 1){
						$plugin = $this->main->getServer()->getPluginManager()->getPlugin("SkyWars");
						if(!$plugin instanceof Plugin) return;
						if(!$plugin->getArenaPlayer($p) instanceof SWArena) return;
						$plugin->getArenaPlayer($p)->remove($p);
						$this->main->getServer()->dispatchCommand($p, "hub");
						return;
					}
				break;
				case UI::ID_MENU_CAPES:
					if($data == null && $data !== 0) return;
					
					if($data == 0){
						UI::requestUI($p, UI::ID_CAPES_EXCLUSIVES);
						return;
					}
					if($data == 1){
						UI::requestUI($p, UI::ID_CAPES_FREE);
						return;
					}
					if($data == 2){
						UI::requestUI($p, UI::ID_CAPES_BUY);
						return;
					}
				break;
				case UI::ID_CAPES_EXCLUSIVES:
					if($data == null && $data !== 0) return;
					
					if($data == 0){
						$this->main->getServer()->dispatchCommand($p, "capes set bc");
						return;
					}
					
					if($data == 1){
						$this->main->getServer()->dispatchCommand($p, "capes set energy");
						return;
					}
					
					if($data == 2){
						$this->main->getServer()->dispatchCommand($p, "capes set firework");
						return;
					}
					
					if($data == 3){
						$this->main->getServer()->dispatchCommand($p, "capes set fire");
						return;
					}
				break;
				case UI::ID_COSMETICS:
					if($data == null && $data !== 0) return;
					
					if($data == 0){
						$this->main->getServer()->dispatchCommand($p, "capes ui");
						return;
					}
					
					if($data == 1){
						$this->main->getServer()->dispatchCommand($p, "fly");
						return;
					}
					if($data == 2){
						$this->main->getServer()->dispatchCommand($p, "cc");
						return;
					}
					if($data == 3){
						$this->main->getServer()->dispatchCommand($p, "p ui");
						return;
					}
				break;
				case UI::ID_CAPES_FREE:
					if($data == null && $data !== 0) return;
					
					if($data == 0){
						$this->main->getServer()->dispatchCommand($p, "capes set ig");
						return;
					}
					
					if($data == 1){
						$this->main->getServer()->dispatchCommand($p, "capes set pickaxe");
						return;
					}

					if($data == 2){
						$this->main->getServer()->dispatchCommand($p, "capes set yt");
						return;
					}
					
				break;
				case UI::ID_CAPES_BUY:
					if($data == null && $data !== 0) return;
					
					if($data == 0){
						$this->main->getServer()->dispatchCommand($p, "capes set turtle");
						return;
					}
					
					if($data == 1){
						$this->main->getServer()->dispatchCommand($p, "capes set rc");
						return;
					}
				break;
				case UI::ID_PARTICLES:
					if($data == null && $data !== 0) return;
					
					if($data == 0){
						$this->main->getServer()->dispatchCommand($p, "p ch");
						return;
					}
					
					if($data == 1){
						$this->main->getServer()->dispatchCommand($p, "p sb");
						return;
					}
					if($data == 2){
						$this->main->getServer()->dispatchCommand($p, "p remove");
						return;
					}
				break;
			}
		}
	}

	public function onPacketReceived(\pocketmine\event\server\DataPacketReceiveEvent $e){
        if($e->getPacket() instanceof \pocketmine\network\mcpe\protocol\LoginPacket){
            if($e->getPacket()->clientData["DeviceOS"] !== null){
                $this->main->os[strtolower($e->getPacket()->username) ?? "unavailable"] = $e->getPacket()->clientData["DeviceOS"];
                $this->main->device[strtolower($e->getPacket()->username) ?? "unavailable"] = $e->getPacket()->clientData["DeviceModel"];
            }
        }
    }

	public function onCommandPreProccess(PlayerCommandPreprocessEvent $ev){
		$p = $ev->getPlayer();
		$msg = $ev->getMessage();

		$cmd = explode(" ", $msg);
		if($cmd[0] == "/ban"){
			$ev->setCancelled(true);
			if($p->hasPermission(Permissions::BAN_CMD_USE)){
				$p->sendMessage("§cUtiliza /punish <jugador> <tiempo> <motivo>");
			}else{
				$p->sendMessage("§cNo tienes permisos para utilizar este comando");
			}
		}
		/*
		if($cmd[0] == "/tell"){
			if(!isset($cmd[0])){
				return;
			}
			if(!isset($cmd[1])){
				return;
			}
			if(!isset($cmd[2])){
				return;
			}
			if($ev->isCancelled()){
				return;
			}
			$text = $cmd;
			unset($cmd[0]);
			unset($cmd[1]);
			$api = new Rank($this->main);
			$r = $this->main->getServer()->getPlayer($cmd[1]);
			if(!$r instanceof Player) return;
			foreach($api->getPlayers("staff", true) as $rP){
				if($rP instanceof Player){
					$rP->sendMessage("§7§o‹".$p->getName()." -> ".$r->getName()."› ".implode(" ", $text));
				}
			}
		}
		*/
		if($cmd[0] == "/me"){
			$api = new Rank($this->main);
			if(!$p->hasPermission(Permissions::ME_CMD_USE)){
				$ev->setCancelled();
				$p->sendMessage("§cNo tienes derecho a utilizar este comando");
			}
		}
		if($cmd[0] == "/say"){
			$api = new Rank($this->main);
			if(!$p->getName() == "PlayekEz" or !$api->userRank($p) == "Staff" or !$api->userRank($p) == "owner" or !$api->userRank($p) == "OverMan"){
				$ev->setCancelled();
				$p->sendMessage("§cNo tienes derecho a utilizar este comando");
			}
		}
	}
	
	public function onChat(PlayerChatEvent $ev){

		$p = $ev->getPlayer();

		$msg = $ev->getMessage();

		$Rank = Rank::userRank($p);
		
		$format = str_replace(["{rank}", "{name}", "{message}", "{color}"], [$Rank, $p->getName(), $msg, Rank::getData($Rank)["color"]], Rank::getData($Rank)["chatFormat"]);
		
		if(isset($this->main->chatColor[$p->getName()])){
			$final = "";
			$len = mb_strlen($msg)-1;
			$colores = ["§b", "§7", "§9", "§d", "§e", "§6", "§f", "§5", "§a"];
			$i = 0;
			while($i <= $len){
				$final .= $colores[mt_rand(0, (count($colores) - 1))].$msg[$i];
				$i++;
			}
			$format = str_replace(["{rank}", "{name}", "{message}", "{color}"], [$Rank, $p->getName(), $final, Rank::getData($Rank)["color"]], Rank::getData($Rank)["chatFormat"]);
		}
		$ev->setFormat($format);
		if(Punish::isMuted($p->getName())){
			$ev->setCancelled();
			$p->sendMessage("§cNo puedes chatear, estas muteado..!");
		}


	}

	

	public function onJoin(PlayerJoinEvent $ev){

		$p = $ev->getPlayer();
		$this->main->getScheduler()->scheduleRepeatingTask(new CoreJoin($p), 20);
		Rank::set($p, Rank::userRank($p));
		$ev->setJoinMessage("§8[§a+§8] §o§b".$p->getName()." §7entro al servidor");
	}

	

	public function onQuit(PlayerQuitEvent $ev){

		$p = $ev->getPlayer();

		if(isset($this->main->perms[$p->getName()])){

			unset($this->main->perms[$p->getName()]);

		}
		
		if(isset($this->main->chatColor[$p->getName()])){

			unset($this->main->chatColor[$p->getName()]);

		}

		$ev->setQuitMessage("");

	}

	

	public function onDamage(EntityDamageEvent $ev){

		$p = $ev->getEntity();

		if($p instanceof Player){

			if(!Utils::isPvP($p->getLevel()->getFolderName())){

				$ev->setCancelled();

				$rand = mt_rand(0,8);

				if($rand == 3){

					if($ev instanceof EntityDamageByEntityEvent && $ev->getDamager() instanceof Player){

						$ev->getDamager()->sendTip("§o§cNo se permite pvp en el lobby");

					}

				}

			}

		}

	}

	

	public function onBreak(BlockBreakEvent $ev){

		$p = $ev->getPlayer();

		if(!Utils::isBuilding($p->getLevel()->getFolderName())){

			$ev->setCancelled();

		}

	}

	

	public function onPlace(BlockPlaceEvent $ev){

		$p = $ev->getPlayer();

		if(!Utils::isBuilding($p->getLevel()->getFolderName())){

			$ev->setCancelled();

		}

	}
	
	public function onExhaust(PlayerExhaustEvent $ev){
		
		$p = $ev->getPlayer();
		$def = $this->main->getServer()->getDefaultLevel()->getFolderName();
		$ext = $p->getLevel()->getFolderName();
		if($ext == $def){
			$ev->setCancelled();

		}
		
	}

	public function onMove(PlayerMoveEvent $ev){

		$p = $ev->getPlayer();

		if($p->getLevel()->getFolderName() == Utils::getHubLevel()->getFolderName()){

			if($p->getY() < 4){

				$p->teleport(Utils::getHubLevel()->getSpawnLocation());

			}

		}

	}

}