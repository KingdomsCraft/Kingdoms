<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 22:44
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class CreateCommand extends SubCommand implements KingdomSubCommand {

	/**
	 * Execute create command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if($sender->isAdmin() or $sender->isOp()) {
				if(isset($args[0]) and !empty($args[0])) {
					$name = strtoupper($args[0]);
					if($this->getPlugin()->getKingdomManager()->isKingdom($name)) {
						$sender->sendKingdomMessage("KINGDOM_CREATE_FAILED_BY_KINGDOM");
					} else {
						$config = $this->getPlugin()->getConfig()->getAll();
						if(strlen($name) <= ((int)$config["max-k-chars"]) and strlen($name) >= ((int)$config["min-k-chars"])) {
							if(isset($args[1]) and !empty($args[1])) {
								$motto = $args;
								unset($motto[0]);
								$motto = implode(" ", $motto);
							} else {
								$motto = "This is an amazing kingdom!";
							}
							$this->getPlugin()->getPluginDatabase()->getKingdomDatabase()->registerKingdom($name, $motto);
							$sender->sendKingdomMessage("KINGDOM_CREATE_SUCCESS");
						} else {
							$sender->sendKingdomMessage("KINGDOM_CREATE_FAILED_BY_CHARS");
						}
					}
				} else {
					$sender->sendKingdomMessage("KINGDOM_CREATE_USAGE");
				}
			} else {
				$sender->sendKingdomMessage("KINGDOM_CREATE_FAILED_BY_RANK");
			}
		}
	}

}