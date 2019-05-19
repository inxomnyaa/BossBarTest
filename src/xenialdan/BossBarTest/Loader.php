<?php

/*
 * BossBarTest
 * A plugin by XenialDan aka thebigsmileXD
 * http://github.com/thebigsmileXD/BossBarTest
 * Demonstration of apibossbar
 */

namespace xenialdan\BossBarTest;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use xenialdan\apibossbar\BossBar;

class Loader extends PluginBase implements Listener
{

    /** @var BossBar */
    public static $bar;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        self::$bar = (new BossBar())->setPercentage(1);//This creates the bar and sets it to full
        $this->getScheduler()->scheduleRepeatingTask(new class extends Task
        {//A simple repeating task that changes the title every second
            public function onRun(int $currentTick)
            {
                foreach (Server::getInstance()->getDefaultLevel()->getPlayers() as $player) {
                    //Change the title
                    Loader::$bar->setTitle(TextFormat::BOLD . '>>' . TextFormat::RESET . ' Hello ' . $player->getName() . ' | Time: ' . TextFormat::GREEN . date('H:i:s') . ' ' . TextFormat::BOLD . '<<');
                }
            }
        }, 20);
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        //add the player to the bar
        self::$bar->addPlayer($ev->getPlayer());
    }
}