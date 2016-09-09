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
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

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
		$event->setPlayerClass(KingdomsPlayer::class);
	}

	/**
	 * Login a player
	 *
	 * @param PlayerLoginEvent $event
	 */
	public function onLogin(PlayerLoginEvent $event) {
		$this->plugin->getPluginDatabase()->getPlayerDatabase()->loginPlayer($event->getPlayer()->getName());
	}

	/**
	 * Update player before he quit the server
	 *
	 * @param PlayerQuitEvent $event
	 */
	public function onQuit(PlayerQuitEvent $event) {
		/** @var KingdomsPlayer $player */
		$player = $event->getPlayer();
		$player->update();
	}

}