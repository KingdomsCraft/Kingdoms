<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 06/08/2016
 * Time: 13:49
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\language\LanguageManager;
use kingdomscraft\economy\Economy;
use pocketmine\command\CommandSender;

class VaultCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute vault command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->gotGuild()) {
				if(isset($args[0]) and !empty($args[0])) {
					if($args[0] = "add") {
						if(isset($args[1]) and !empty($args[1])) {
							$rubies = (int)$args[1];
							if($rubies > 0) {
								$economy = Economy::getInstance();
								if($economy->getRubies($sender) >= $rubies) {
									$economy->removeRubies($sender, $rubies);
									$sender->getGuild()->addRubies($rubies);
									$sender->sendKingdomMessage("GUILD_VAULT_SUCCESS");
								} else {
									$sender->sendKingdomMessage("GUILD_VAULT_FAILED_BY_MONEY");
								}
							} else {
								$sender->sendKingdomMessage("GUILD_VAULT_USAGE");
							}
						} else {
							$sender->sendKingdomMessage("GUILD_VAULT_USAGE");
						}
					} else {
						$sender->sendKingdomMessage("GUILD_VAULT_USAGE");
					}
				} else {
					$message = LanguageManager::getInstance()->getMessage("GUILD_VAULT_MONEY");
					$message = str_replace("{amount}", $sender->getGuild()->getRubies(), $message);
					$sender->sendMessage($message);
				}
			} else {
				$sender->sendKingdomMessage("GUILD_VAULT_FAILED_BY_GUILD");
			}
		} else {
			$sender->sendMessage("Please, run this command in game");
		}
	}

}