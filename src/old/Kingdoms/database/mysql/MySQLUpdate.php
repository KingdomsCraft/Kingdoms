<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 16:40
 */

namespace Kingdoms\database\mysql;

use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\scheduler\PluginTask;

class MySQLUpdate extends PluginTask {

	/**
	 * MySQLUpdate constructor.
	 *
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		parent::__construct($plugin);
	}

	public function onRun($currentTick) {
		/** @var Main $plugin */
		$plugin = $this->getOwner();
		/** @var KingdomsPlayer $player */
		foreach($plugin->getServer()->getOnlinePlayers() as $player) {
			$player->update();
		}
		foreach($plugin->getKingdomManager()->getKingdoms() as $kingdom) {
			$kingdom->update();
		}
		foreach($plugin->getGuildManager()->getGuilds() as $guild) {
			$guild->update();
		}
	}

}