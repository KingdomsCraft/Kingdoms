<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 17:10
 */

namespace Kingdoms;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;

class EventListener implements Listener {

    /** @var Main */
    private $plugin;

    /**
     * EventListener constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }

    /**
     * Set as main player class KingdomsPlayer
     *
     * @param PlayerCreationEvent $event
     */
    public function onCreation(PlayerCreationEvent $event) {
        $event->setPlayerClass(KingdomPlayer::class);
    }

}