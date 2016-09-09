<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 05/08/2016
 * Time: 16:07
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use kingdomscraft\economy\Economy;
use pocketmine\command\CommandSender;

class CreateCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute create command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotKingdom()) {
				if($sender->gotGuild()) {
					$sender->sendKingdomMessage("GUILD_CREATE_FAILED_BY_GUILD");
				} else {
					if(isset($args[0]) and !empty($args[0])) {
						if($this->getPlugin()->getGuildManager()->isGuild($args[0])) {
							$sender->sendKingdomMessage("GUILD_CREATE_FAILED_BY_NAME");
						} else {
							$economy = Economy::getInstance();
							if($economy->getLevel($sender) >= (int)$this->getPlugin()->getConfig()->get("guild-create-level")) {
								if($economy->getRubies($sender) >= (int)$this->getPlugin()->getConfig()->get("guild-create-price")) {
									$economy->removeRubies($sender, (int)$this->getPlugin()->getConfig()->get("guild-create-price"));
									if(isset($args[1]) and !empty($args[1])) {
										$motto = $args;
										unset($motto[0]);
										$motto = implode(" ", $motto);
									} else {
										$motto = "This is my amazing guild!";
									}
									$this->getPlugin()->getPluginDatabase()->getGuildDatabase()->registerGuild($args[0], $motto, $sender->getKingdom()->getName(), $sender->getName());
									$sender->sendKingdomMessage("GUILD_CREATE_SUCCESS");
								} else {
									$sender->sendKingdomMessage("GUILD_CREATE_FAILED_BY_MONEY");
								}
							} else {
								$sender->sendKingdomMessage("GUILD_CREATE_FAILED_BY_LEVEL");
							}
						}
					} else {
						$sender->sendKingdomMessage("GUILD_CREATE_USAGE");
					}
				}
			} else {
				$sender->sendKingdomMessage("GUILD_CREATE_FAILED_BY_KINGDOM");
			}
		} else {
			$sender->sendMessage("Please, run this command in game!");
		}
	}

}