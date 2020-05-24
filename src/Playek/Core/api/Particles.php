<?php

namespace Playek\Core\api;

use Playek\Core\Main;

class Particles {

    public function __construct(Main $main){
        $this->main = $main;
    }

    public function isParticlePlayer(string $name): bool {
		if(isset($this->main->particle[$name])){
			return true;
		}
		return false;
	}

	public function setParticle(string $name, string $particle): void{
        $this->main->particle[$name] = $particle;
    }

    public function remove(string $name): void {
        if(isset($this->main->particle[$name])){
            unset($this->main->particle[$name]);
        }
        return;
    }

    public function getAll(): array {
        return $this->main->particle;
    }
}
?>