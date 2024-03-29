<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 01/08/2016
 * Time: 12:13
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class InfoCommand extends SubCommand implements KingdomSubCommand {

	/**
	 * Execute info command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if(isset($args[0]) and !empty($args[0])) {
				$this->getPlugin()->getPluginDatabase()->getKingdomDatabase()->showKingdomInfo($args[0], $sender->getName());
			} else {
				if($sender->gotKingdom()) {
					$this->getPlugin()->getPluginDatabase()->getKingdomDatabase()->showKingdomInfo($sender->getKingdom()->getName(), $sender->getName());
				} else {
					$sender->sendKingdomMessage("KINGDOM_INFO_FAILED_ESCAPE");
				}
			}
		}
	}

}