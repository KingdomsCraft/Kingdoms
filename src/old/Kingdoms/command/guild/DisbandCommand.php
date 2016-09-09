<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 12/08/2016
 * Time: 10:42
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class DisbandCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute disband command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotGuild()) {
				if($sender->isLeader()) {
					$this->getPlugin()->getPluginDatabase()->getGuildDatabase()->deleteGuild($sender->getGuild()->getName());
					foreach($sender->getGuild()->getOnlinePlayers() as $player) {
						if($player->isLeader()) {
							$player->setLeader(false);
						}
						$player->setGuild(null);
						$player->sendKingdomMessage("GUILD_DISBANDED");
					}
				} else {
					$sender->sendKingdomMessage("GUILD_DISBAND_FAILED_BY_RANK");
				}
			} else {
				$sender->sendKingdomMessage("GUILD_DISBAND_FAILED_BY_GUILD");
			}
		} else {
			$sender->sendMessage("Please, run this command in game");
		}
	}

}