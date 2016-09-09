<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 06/08/2016
 * Time: 15:23
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\models\guild\Guild;
use kingdomscraft\economy\Economy;
use pocketmine\command\CommandSender;

class ClassCommand extends SubCommand implements GuildSubCommand {

	/**
	 * ToDo:
	 *
	 * CHECK IF A GUILD ALREADY GOT A CLASS
	 *
	 */

	/**
	 * Execute class command
	 *
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args) {
		if($sender instanceof KingdomsPlayer) {
			if(isset($args[0]) and !empty($args[0])) {
				switch(strtolower($args[0])) {
					case "assassin":
						if($sender->gotGuild()) {
							if($sender->isLeader()) {
								if(Economy::getInstance()->getRubies($sender) >= (int)$this->getPlugin()->getConfig()->get("assassin-class-price")) {
									Economy::getInstance()->removeRubies($sender, (int)$this->getPlugin()->getConfig()->get("assassin-class-price"));
									$sender->getGuild()->setClass(Guild::CLASS_ASSASSIN);
									$sender->sendKingdomMessage("GUILD_CLASS_ASSASSIN");
								} else {
									$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_RUBIES");
								}
							} else {
								$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_RANK");
							}
						} else {
							$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_GUILD");
						}
						break;
					case "farmer":
						if($sender->gotGuild()) {
							if($sender->isLeader()) {
								if(Economy::getInstance()->getRubies($sender) >= (int)$this->getPlugin()->getConfig()->get("farmer-class-price")) {
									Economy::getInstance()->removeRubies($sender, (int)$this->getPlugin()->getConfig()->get("farmer-class-price"));
									$sender->getGuild()->setClass(Guild::CLASS_FARMER);
									$sender->sendKingdomMessage("GUILD_CLASS_FARMER");
								} else {
									$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_RUBIES");
								}
							} else {
								$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_RANK");
							}
						} else {
							$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_GUILD");
						}
						break;
					case "warrior":
						if($sender->gotGuild()) {
							if($sender->isLeader()) {
								if(Economy::getInstance()->getRubies($sender) >= (int)$this->getPlugin()->getConfig()->get("warrior-class-price")) {
									Economy::getInstance()->removeRubies($sender, (int)$this->getPlugin()->getConfig()->get("warrior-class-price"));
									$sender->getGuild()->setClass(Guild::CLASS_WARRIOR);
									$sender->sendKingdomMessage("GUILD_CLASS_WARRIOR");
								} else {
									$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_RUBIES");
								}
							} else {
								$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_RANK");
							}
						} else {
							$sender->sendKingdomMessage("GUILD_CLASS_FAILED_BY_GUILD");
						}
						break;
					case "info":
						$sender->sendKingdomMessage("GUILD_CLASS_INFO");
						break;
					default:
						$sender->sendKingdomMessage("GUILD_CLASS_USAGE");
						break;
				}
			} else {
				$sender->sendKingdomMessage("GUILD_CLASS_INFO");
			}
		} else {
			$sender->sendMessage("Please, run this command in game");
		}
	}

}