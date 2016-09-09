<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 05/08/2016
 * Time: 16:48
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\language\LanguageManager;
use kingdomscraft\economy\Economy;
use pocketmine\command\CommandSender;

class InviteCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute invite command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotGuild()) {
				if($sender->isLeader()) {
					if($this->getPlugin()->getPluginDatabase()->getGuildDatabase()->isFree($sender->getGuild()->getName())) {
						if(isset($args[0]) and !empty($args[0])) {
							$player = $this->getPlugin()->getServer()->getPlayer($args[0]);
							if($player instanceof KingdomsPlayer and $player->isOnline()) {
								if(Economy::getInstance()->getLevel($player) >= (int)$this->getPlugin()->getConfig()->get("guild-invite-level")) {
									$player->addInvitation($sender, $sender->getGuild());
									$message = LanguageManager::getInstance()->getMessage("GUILD_INVITATION");
									$message = str_replace("{sender}", $sender->getName(), $message);
									$message = str_replace("{guild}", $sender->getGuild()->getName(), $message);
									$player->sendMessage($message);
									$sender->sendKingdomMessage("GUILD_INVITE_SUCCESS");
								} else {
									$sender->sendKingdomMessage("GUILD_INVITE_FAILED_BY_LEVEL");
								}
							} else {
								$sender->sendKingdomMessage("GUILD_INVITE_FAILED_BY_PLAYER");
							}
						} else {
							$sender->sendKingdomMessage("GUILD_INVITE_USAGE");
						}
					} else {
						$sender->sendKingdomMessage("GUILD_INVITE_FAILED_BY_SLOTS");
					}
				} else {
					$sender->sendKingdomMessage("GUILD_INVITE_FAILED_BY_RANK");
				}
			} else {
				$sender->sendKingdomMessage("GUILD_INVITE_FAILED_BY_GUILD");
			}
		} else {
			$sender->sendMessage("Please, run this command in game");
		}
	}

}