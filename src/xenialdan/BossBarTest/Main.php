<?php

/*
 * BossBarTest
 * A plugin by XenialDan aka thebigsmileXD
 * http://github.com/thebigsmileXD/BossBarTest
 * Demonstration of the BossBarAPI
 */
namespace xenialdan\BossBarTest;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use xenialdan\BossBarAPI\API;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{
	public $eid = null;

	public function onEnable(){
		if(($API = $this->getServer()->getPluginManager()->getPlugin("BossBarAPI")) !== null){}
		else{
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new SendTask($this), 20);
	}

	public function onJoin(PlayerJoinEvent $ev){
		if($this->eid === null){
			$this->eid = API::addBossBar([$ev->getPlayer()], 'Joining..');
			$this->getServer()->getLogger()->debug($this->eid === NULL?'Couldn\'t add BossBar':'Successfully added BossBar with EID: ' . $this->eid);
		}
		else{
			API::sendBossBarToPlayer($ev->getPlayer(), $this->eid, 'Joining..');
			$this->getServer()->getLogger()->debug('Sendt BossBar with existing EID: ' . $this->eid);
		}
	}

	public function sendBossBar(){
		if($this->eid === null) return;
		foreach($this->getServer()->getDefaultLevel()->getPlayers() as $player){
			API::setTitle(TextFormat::BOLD . '>>' . TextFormat::RESET . ' Hello ' . $player->getName() . ' | Time: ' . TextFormat::GREEN . date('H:i:s') . ' ' . TextFormat::BOLD . '<<', $this->eid);
			API::setPercentage(100, $this->eid);
		}
	}
}