<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 18:09
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class HealCommand extends SubCommand implements KingdomSubCommand {

	/**
	 * Execute heal command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotKingdom()) {
				if($sender->isRankSuperior(KingdomsPlayer::KINGDOM_RANK_KING) or $sender->isAdmin()) {
					if(isset($args[0]) and !empty($args[0])) {
						$player = $this->getPlugin()->getServer()->getPlayer($args[0]);
						if($player instanceof KingdomsPlayer) {
							if($player->getKingdom() == $sender->getKingdom()) {
								$player->setHealth($player->getMaxHealth());
								$player->sendKingdomMessage("HEALED_YOU");
								$sender->sendKingdomMessage("HEAL_SUCCESS");
							} else {
								$sender->sendKingdomMessage("HEAL_FAILED_BY_PLAYER_KINGDOM_NOT_VALID");
							}
						} else {
							$sender->sendKingdomMessage("HEAL_FAILED_BY_PLAYER");
						}
					} else {
						$sender->sendKingdomMessage("HEAL_USAGE");
					}
				} else {
					$sender->sendKingdomMessage("HEAL_FAILED_BY_RANK");
				}
			} else {
				$sender->sendKingdomMessage("HEAL_FAILED_BY_KINGDOM");
			}
		}
	}

}