<?php



namespace Playek\Core\task;



use pocketmine\Player;



use Playek\Core\api\Punish;

use Playek\Core\Main;



class PunishTask extends \pocketmine\scheduler\Task {

	

	private $main;

	

	public function __construct(Main $main){

		$this->main = $main;

	}

	

	public function onRun(int $currentTick): void {

		foreach(Punish::getPlayers() as $data){

			if(Punish::isBanned($data["NAME"])){

				if(Punish::getData($data["NAME"])["TYPE"] == "T"){

					$time = Punish::getData($data["NAME"])["TIME"];

					$time-=1;

					Punish::setTime($data["NAME"], $time); 

					if($time == 0){

						Punish::remove($data["NAME"]);

					}

				}

			}
			if(Punish::isMuted($data["NAME"])){
				if(Punish::getData($data["NAME"])["TYPE"] == "T"){
					$time = Punish::getData($data["NAME"])["TIME"];

					$time-=1;

					Punish::setTime($data["NAME"], $time); 
					if($time == 0){
						if($this->main->getServer()->getPlayer($data["NAME"]) instanceof Player){
							$this->main->getServer()->getPlayer($data["NAME"])->sendMessage("§aFelicidades, has sido desmuteado correctamente");
						}
						Punish::remove($data["NAME"]);
					}
				}
			}

		}

	}

}