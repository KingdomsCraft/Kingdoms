<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 22:15
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class SetNoblemanCommand extends SubCommand implements KingdomSubCommand {

	/**
	 * Execute setnobleman command
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
						$player->setKingdomRank(KingdomsPlayer::KINGDOM_RANK_NOBLEMAN);
					}
					$this->getPlugin()->getPluginDatabase()->getPlayerDatabase()->setPlayerRank($args[0], KingdomsPlayer::KINGDOM_RANK_NOBLEMAN);
					$sender->sendKingdomMessage("KINGDOM_SETNOBLEMAN_SUCCESS");
				} else {
					$sender->sendKingdomMessage("KINGDOM_SETNOBLEMAN_USAGE");
				}
			} else {
				$sender->sendKingdomMessage("KINGDOM_SETNOBLEMAN_FAILED_BY_RANK");
			}
		} else {
			if(isset($args[0]) and !empty($args[0])) {
				$player = $this->getPlugin()->getServer()->getPlayer($args[0]);
				if($player instanceof KingdomsPlayer) {
					$player->setKingdomRank(KingdomsPlayer::KINGDOM_RANK_NOBLEMAN);
				}
				$this->getPlugin()->getPluginDatabase()->getPlayerDatabase()->setPlayerRank($args[0], KingdomsPlayer::KINGDOM_RANK_NOBLEMAN);
				$sender->sendMessage("You set his rank successfully!");
			} else {
				$sender->sendMessage("Please, add a new parameter with the player name!");
			}
		}
	}

}