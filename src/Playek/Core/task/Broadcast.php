<?php



namespace Playek\Core\task;



use pocketmine\Player;



use Playek\Core\Main;



class Broadcast extends \pocketmine\scheduler\Task {

	

	private $main;

	private $id = 0;

	

	private $messages = [

	"Aprovecha nuestras ofertas de rangos!",
	"Disfruta de nuestros minijuegos!",
	"Utiliza /report solo en casos importantes",
	"Recuerda seguirnos via twitter en nuestro perfil §b@PlayOver11",
	];

	

	public function __construct(Main $main){

		$this->main = $main;

	}

	

	public function onRun($currentTick){

		$this->id = mt_rand(0, count($this->messages) - 1);

		foreach($this->main->getServer()->getOnlinePlayers() as $p){

			if($p instanceof Player){

				$p->sendMessage("§r§3» §7".$this->messages[$this->id]);

			}

		}

	}

}