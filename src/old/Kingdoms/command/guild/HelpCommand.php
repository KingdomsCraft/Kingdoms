<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 04/08/2016
 * Time: 14:41
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\language\LanguageManager;
use pocketmine\command\CommandSender;

class HelpCommand extends SubCommand implements GuildSubCommand {

	/**
	 * Execute help command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if(isset($args[0])) {
				$page = (int)$args[0];
				if($page <= 0) {
					$page = 1;
				} elseif($page > 4) {
					$page = 4;
				}
			} else {
				$page = 1;
			}
			$sender->sendMessage(str_replace("{page}", $page, LanguageManager::getInstance()->getMessage("GUILD_HELP_HEADER")));
			$sender->sendKingdomMessage("GUILD_HELP_PAGE_{$page}");
		}
	}

}