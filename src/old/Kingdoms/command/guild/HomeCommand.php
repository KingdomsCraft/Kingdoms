<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 06/08/2016
 * Time: 15:18
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;

class HomeCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute home command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotGuild()) {
				if($sender->getGuild()->getHomePosition() instanceof Position) {
					$sender->teleport($sender->getGuild()->getHomePosition());
					$sender->sendKingdomMessage("GUILD_HOME_SUCCESS");
				} else {
					$sender->sendKingdomMessage("GUILD_HOME_FAILED_BY_POSITION");
				}
			} else {
				$sender->sendKingdomMessage("GUILD_HOME_FAILED_BY_GUILD");
			}
		} else {
			$sender->sendMessage("Please, run this command in game");
		}
	}

}