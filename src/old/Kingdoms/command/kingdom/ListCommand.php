<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 21:48
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\language\LanguageManager;
use pocketmine\command\CommandSender;

class ListCommand extends SubCommand implements KingdomSubCommand {

	/**
	 * Execute list command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			$sender->sendKingdomMessage("LIST_HEADER");
			foreach($this->getPlugin()->getKingdomManager()->getKingdoms() as $kingdom) {
				$message = LanguageManager::getInstance()->getMessage("LIST_MESSAGE");
				$message = str_replace("{kingdom}", $kingdom->getName(), $message);
				$message = str_replace("{online}", count($kingdom->getPlayersByKingdom()), $message);
				$sender->sendMessage($message);
			}
		}
	}

}