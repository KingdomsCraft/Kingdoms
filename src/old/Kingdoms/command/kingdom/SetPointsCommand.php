<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 04/08/2016
 * Time: 7:02
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class SetPointsCommand extends SubCommand implements KingdomSubCommand {

	/**
	 * Execute setpoints command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if(isset($args[0]) and !empty($args[0])) {
				$amount = (int)$args[0];
				if(is_int($amount)) {
					if(isset($args[1])) {
						if($this->getPlugin()->getKingdomManager()->isKingdom($args[1])) {
							$this->getPlugin()->getKingdomManager()->getKingdom($args[1])->setPoints($amount);
							$sender->sendKingdomMessage("KINGDOM_SETPOINTS_SUCCESS");
						} else {
							$sender->sendKingdomMessage("KINGDOM_SETPOINTS_FAILED_BY_KINGDOM_2");
						}
					} else {
						if($sender->gotKingdom()) {
							$sender->getKingdom()->setPoints($amount);
							$sender->sendKingdomMessage("KINGDOM_SETPOINTS_SUCCESS");
						} else {
							$sender->sendKingdomMessage("KINGDOM_SETPOINTS_FAILED_BY_KINGDOM");
						}
					}
				} else {
					$sender->sendKingdomMessage("KINGDOM_SETPOINTS_FAILED_BY_NUMBER");
				}
			} else {
				$sender->sendKingdomMessage("KINGDOM_SETPOINTS_USAGE");
			}
		}
	}

}