<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 06/08/2016
 * Time: 13:42
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class LeaveCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute leave command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotGuild()) {
				if($sender->isLeader()) {
					$sender->sendKingdomMessage("GUILD_LEAVE_FAILED_BY_RANK");
				} else {
					$sender->sendKingdomMessage("GUILD_LEAVE_SUCCESS");
					$sender->setGuild(null);
				}
			} else {
				$sender->sendKingdomMessage("GUILD_LEAVE_FAILED_BY_GUILD");
			}
		} else {
			$sender->sendMessage("Please, run this command in game");
		}
	}

}