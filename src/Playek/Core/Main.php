<?php



namespace Playek\Core;



use pocketmine\plugin\PluginBase;



use pocketmine\Player;

use pocketmine\Server;



use pocketmine\utils\Config;



use Playek\Core\utils\Utils;



use Playek\Core\task\Scoreboard;

use Playek\Core\task\Broadcast;

use Playek\Core\task\PunishTask;

use Playek\Core\task\Motd;

use Playek\Core\task\ParticleTask;

use Playek\Core\commands\Hub;

use Playek\Core\commands\RankCommand;

use Playek\Core\commands\BanCommand;

use Playek\Core\commands\WpCommand;

use Playek\Core\commands\ReportCommand;

use Playek\Core\commands\MuteCommand;

use Playek\Core\commands\FlyCommand;

use Playek\Core\commands\ScCommand;

use Playek\Core\commands\ChatColorCommand;

use Playek\Core\commands\CapesCommand;

use Playek\Core\commands\ParticlesCommand;
class Main extends PluginBase {

	

	public $players = [];

	public $perms = [];

	private static $instance;

	public $listOfOs = ["Unknown", "Android", "iOS", "macOS", "FireOS", "GearVR", "HoloLens", "Windows10", "Windows", "EducalVersion","Dedicated", "PlayStation4", "Switch", "XboxOne"];
	public $os = [];
	public $device = [];
	
	public $reports = [];
	
	public $chatColor = [];
	public $particle = [];
	public $capePlayer = [];

	public const IP_SERVER = "mc.playover.cf";
	public const PORT = 19132;

	public function onEnable() {

		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . "capes/");
		
		foreach(["BlueCreeper", "Energy", "Firework", "Fire", "Pickaxe", "Turtle", "Red Creeper", "Iron Golem", "Youtube"] as $cape){
			$this->saveResource("capes/".$cape.".png");
		}
		self::$instance = $this;

		$this->getServer()->loadLevel(Utils::getHubLevelToString());

		$this->registerEvents();

		$this->registerTask();

		$this->registerCommands();

	}


	public static function getInstance(): Main {

		return self::$instance;

	}

	

	public function registerTask() {

		$this->getScheduler()->scheduleRepeatingTask(new Scoreboard($this), 10);

		$this->getScheduler()->scheduleRepeatingTask(new Broadcast($this), 20 * 25);

		$this->getScheduler()->scheduleRepeatingTask(new PunishTask($this), 20);

		$this->getScheduler()->scheduleRepeatingTask(new Motd($this), 80);

		$this->getScheduler()->scheduleRepeatingTask(new ParticleTask($this), 15);
	}

	public function registerEvents() {

		$this->getServer()->getPluginManager()->registerEvents(new CoreEvent($this), $this);

	}

	

	public function registerCommands() {

		$this->getServer()->getCommandMap()->register("hub", new Hub($this));

		$this->getServer()->getCommandMap()->register("rank", new RankCommand($this));

		$this->getServer()->getCommandMap()->register("punish", new BanCommand($this));

		$this->getServer()->getCommandMap()->register("wp", new WpCommand($this));

		$this->getServer()->getCommandMap()->register("report", new ReportCommand($this));

		$this->getServer()->getCommandMap()->register("mute", new MuteCommand($this));

		$this->getServer()->getCommandMap()->register("fly", new FlyCommand($this));

		$this->getServer()->getCommandMap()->register("sc", new ScCommand($this));
		
		$this->getServer()->getCommandMap()->register("cc", new ChatColorCommand($this));
		
		$this->getServer()->getCommandMap()->register("capes", new CapesCommand($this));

		$this->getServer()->getCommandMap()->register("p", new ParticlesCommand($this));
	}

	

	public function newConfig(string $name, string $ext = ".yml"): Config {

		return new Config($this->getDataFolder() . $name . $ext, Config::YAML);

	}

	

	public function timeToString($seconds) : string {

		$hours = floor($seconds / 3600);

		$minutes = floor($seconds % 3600 / 60);

        $seconds = $seconds % 60;

		$data = [$hours, $minutes, $seconds];

        $time = "";

                                    

        if ($data[0]) {

            if ($data[0] < 10) {

                $time .= "0" . $data[0] . ":";

            } else {

                $time .= $data[0] . ":";

            }

        }



        if ($data[1] < 10) {

            $time .= "0" . $data[1] . ":";

        } else {

            $time .= $data[1] . ":";

        }



        if ($data[2] < 10) {

            $time .= "0" . $data[2];

        } else {

            $time .= $data[2];

        }

        

        return $time;

    }

	public static function iAlreadyBuy(string $name, string $article): bool{
		$cfg = self::getInstance()->newConfig("buy");
		if($cfg->get($name) == null) return false;
		if(is_array($cfg->get($name))){
			if(in_array($article, $cfg->get($name))){
				return true;
			}
		}
		return false;
	}

	public function getAttachment(Player $player){

		if(!isset($this->perms[$player->getName()])){

			$this->perms[$player->getName()] = $player->addAttachment($this, $player->getName());

		}

		return $this->perms[$player->getName()];

	}
	
	public function playSound(Player $player, string $soundName, int $pitch = 1, int $volumen = 20){
		$pk = new \pocketmine\network\mcpe\protocol\PlaySoundPacket();
		$pk->soundName = $soundName;
		$pk->volume = $volumen;
		$pk->pitch = $pitch;
		$pk->x = $player->x;
		$pk->y = $player->y;
		$pk->z = $player->z;
		$player->dataPacket($pk);
		return;
	}

	public function getPlayerOs(Player $player) : ?string{
        $name = strtolower($player->getName());
        if(!isset($this->os[$name]) OR $this->os[$name] == null) return "No Device";
        return $this->listOfOs[$this->os[$name]];
	}
	
	public function getPlayerDevice(Player $player) : ?string{
        $name = strtolower($player->getName());
        if(!isset($this->device[$name]) OR $this->device[$name] == null) return null;
        return $this->device[$name];
    }

	public function addArticle(string $name, string $article) {
		$cfg = $this->newConfig("buy");
		if($cfg->get($name) != null){
			$articles = $cfg->get($name);
			$articles[] = $article;
			$cfg->set($name, $articles);
			$cfg->save();
			return;
		}else{
			$cfg->set($name, [$article]);
			$cfg->save();
			return;
		}
		return;
	}
}