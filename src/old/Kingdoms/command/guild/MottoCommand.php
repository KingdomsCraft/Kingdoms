<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 06/08/2016
 * Time: 14:08
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class MottoCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute motto command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotGuild()) {
				if($sender->isLeader()) {
					if(isset($args[0]) and !empty($args[0])) {
						$message = $args;
						unset($message[0]);
						$sender->getGuild()->setMotto(implode(" ", $message));
						$sender->sendKingdomMessage("GUILD_MOTTO_SUCCESS");
					} else {
						$sender->sendKingdomMessage("GUILD_MOTTO_USAGE");
					}
				} else {
					$sender->sendKingdomMessage("GUILD_MOTTO_FAILED_BY_RANK");
				}
			} else {
				$sender->sendKingdomMessage("GUILD_MOTTO_FAILED_BY_GUILD");
			}
		} else {
			$sender->sendMessage("Please, run this command in game");
		}
	}

}