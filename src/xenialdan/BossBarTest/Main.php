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
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\Server;
use pocketmine\Player;

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
		$this->eid = API::addBossBar([$ev->getPlayer()], sprintf('Hello %s | Time: %s', $ev->getPlayer()->getName(), date('H:i:s')));
		$this->getServer()->getLogger()->debug($this->eid === NULL?'Couldn\'t add BossBar':'Successfully added BossBar for EID: ' . $this->eid);
	}

	public function sendBossBar(){
		if($this->eid === null) return;
		foreach(Server::getInstance()->getDefaultLevel()->getPlayers() as $player){
			API::setTitle(sprintf('Hello %s | Time: %s', $player->getName(), date('H:i:s')), $this->eid);
		}
	}

	public function levelChangeRemoveOrAdd(EntityLevelChangeEvent $ev){
		if(!$ev->getEntity() instanceof Player) return;
		if($this->eid === null) return;
		if($ev->getTarget()->getId() === Server::getInstance()->getDefaultLevel()->getId()){ // Only bar in Lobby
			API::sendBossBarToPlayer($ev->getEntity(), $this->eid, sprintf('Hello %s | Time: %s', $ev->getEntity()->getName(), date('H:i:s')));
		}
		else{
			API::removeBossBar([$ev->getEntity()], $this->eid);
		}
	}
}