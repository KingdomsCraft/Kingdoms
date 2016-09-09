<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 06/08/2016
 * Time: 14:42
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class SetHomeCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute sethome command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotGuild()) {
				if($sender->isLeader()) {
					if(in_array($sender->getLevel()->getName(), $this->getPlugin()->getConfig()->get("guild-sethome-levels"))) {
						$sender->getGuild()->setHomePosition($sender->getPosition());
						$sender->sendKingdomMessage("GUILD_SETHOME_SUCCESS");
					} else {
						$sender->sendKingdomMessage("GUILD_SETHOME_FAILED_BY_WORLD");
					}
				} else {
					$sender->sendKingdomMessage("GUILD_SETHOME_FAILED_BY_RANK");
				}
			} else {
				$sender->sendKingdomMessage("GUILD_SETHOME_FAILED_BY_GUILD");
			}
		} else {
			$sender->sendMessage("Please, run this command in game!");
		}
	}

}