<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 22:17
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class SetCitizenCommand extends SubCommand implements KingdomSubCommand {

	/**
	 * Execute seticitizen command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->isAdmin()) {
				if(isset($args[0]) and !empty($args[0])) {
					$player = $this->getPlugin()->getServer()->getPlayer($args[0]);
					if($player instanceof KingdomsPlayer) {
						$player->setKingdomRank(KingdomsPlayer::KINGDOM_RANK_CITIZEN);
					}
					$this->getPlugin()->getPluginDatabase()->getPlayerDatabase()->setPlayerRank($args[0], KingdomsPlayer::KINGDOM_RANK_CITIZEN);
					$sender->sendKingdomMessage("KINGDOM_SETCITIZEN_SUCCESS");
				} else {
					$sender->sendKingdomMessage("KINGDOM_SETCITIZEN_USAGE");
				}
			} else {
				$sender->sendKingdomMessage("KINGDOM_SETCITIZEN_FAILED_BY_RANK");
			}
		} else {
			if(isset($args[0]) and !empty($args[0])) {
				$player = $this->getPlugin()->getServer()->getPlayer($args[0]);
				if($player instanceof KingdomsPlayer) {
					$player->setKingdomRank(KingdomsPlayer::KINGDOM_RANK_CITIZEN);
				}
				$this->getPlugin()->getPluginDatabase()->getPlayerDatabase()->setPlayerRank($args[0], KingdomsPlayer::KINGDOM_RANK_CITIZEN);
				$sender->sendMessage("You set his rank successfully!");
			} else {
				$sender->sendMessage("Please, add a new parameter with the player name!");
			}
		}
	}

}