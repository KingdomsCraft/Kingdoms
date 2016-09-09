<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 12/08/2016
 * Time: 11:06
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class KickCommand extends SubCommand implements GuildSubCommand {

	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotGuild()) {
				if($sender->isLeader()) {
				} else {
					$sender->sendKingdomMessage("GUILD_KICK_FAILED_BY_RANK");
				}
			} else {
				$sender->sendKingdomMessage("GUILD_KICK_FAILED_BY_GUILD");
			}
		} else {
			$sender->sendMessage("Please, run this command in game");
		}
	}

}