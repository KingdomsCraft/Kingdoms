<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 23/07/2016
 * Time: 17:32
 */

namespace Kingdoms;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Events implements Listener {

    /** @var Base */
    private $plugin;

    /**
     * Events constructor.
     * @param Base $plugin
     */
    public function __construct(Base $plugin) {
        $this->plugin = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }

    /**
     * Change Player class by KingdomPlayer class
     *
     * @param PlayerCreationEvent $event
     */
    public function onCreation(PlayerCreationEvent $event) {
        $event->setPlayerClass(KingdomPlayer::class);
    }

    /**
     * Initialize a player and send message JOIN_KINGDOM_MESSAGE if player isn't in a kingdom
     *
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event) {
        /** @var KingdomPlayer $player */
        $player = $event->getPlayer();
        $player->initialize();
        if(!$player->gotKingdom()) {
            $player->sendKingdomMessage("JOIN_KINGDOM_MESSAGE");
        }
    }

    /**
     * Store player data on database and finalize guild data.
     *
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event) {
        /** @var KingdomPlayer $player */
        $player = $event->getPlayer();
        $player->update();
        if($player->gotGuild()) {
            $this->plugin->getGuildManager()->finalizeGuild($player->getGuild());
        }
    }

    /**
     * Tries to send messages only to players in the same chat room.
     *
     * @param PlayerChatEvent $event
     */
    public function onChat(PlayerChatEvent $event) {
        /** @var KingdomPlayer $player */
        $player = $event->getPlayer();
        $format = $event->getFormat();
        /** @var KingdomPlayer $user */
        foreach($this->plugin->getServer()->getOnlinePlayers() as $user) {
            if($player->getChatRoom() == 0) {
                if($user->getChatRoom() == 0) {
                    $user->sendMessage($format);
                }
            }
            else {
                if($user->getChatRoom() == 1 and $user->getKingdom() == $player->getKingdom()) {
                    $user->sendMessage($format);
                }
            }
        }
    }

}