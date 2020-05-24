<?php

namespace Playek\Core\events;

use pocketmine\Player;

use Playek\Core\Main;

class PlayerHubEvent extends \pocketmine\event\Event implements \pocketmine\event\Cancellable {
	
	private $player;
	private $message = null;
	
	public function __construct(Player $player){
		$this->player = $player;
	}
	
	public function getPlayer(): Player {
		return $this->player;
	}
	
	public function setMessage(string $message) {
		$this->message = $message;
	}
	
	public function getMessage() {
		return $this->message;
	}
}