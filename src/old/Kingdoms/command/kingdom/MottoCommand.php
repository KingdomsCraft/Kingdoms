<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 18:31
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class MottoCommand extends SubCommand implements KingdomSubCommand {

	/**
	 * Execute motto command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotKingdom()) {
				if($sender->isRankSuperior(KingdomsPlayer::KINGDOM_RANK_KING) or $sender->isAdmin()) {
					if(isset($args[0]) and !empty($args[0])) {
						$message = implode(" ", $args);
						$sender->getKingdom()->setMotto($message);
						$this->getPlugin()->getPluginDatabase()->getKingdomDatabase()->updateKingdom($sender->getKingdom());
						$sender->sendKingdomMessage("KINGDOM_MOTTO_SUCCESS");
					} else {
						$sender->sendKingdomMessage("KINGDOM_MOTTO_USAGE");
					}
				} else {
					$sender->sendKingdomMessage("KINGDOM_MOTTO_FAILED_BY_RANK");
				}
			} else {
				$sender->sendKingdomMessage("KINGDOM_MOTTO_FAILED_BY_KINGDOM");
			}
		}
	}

}