<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 04/08/2016
 * Time: 12:48
 */

namespace Kingdoms\command;

use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class GuildCommand extends Command {

	/** @var Main */
	private $plugin;

	/** @var CommandManager */
	private $commandManager;

	/**
	 * GuildCommand constructor.
	 *
	 * @param CommandManager $commandManager
	 */
	public function __construct(CommandManager $commandManager) {
		$this->commandManager = $commandManager;
		$this->plugin = $commandManager->getPlugin();
		parent::__construct("guild", "Main guild command", "Usage: /g", ["g"]);
	}

	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(isset($args[0])) {
			switch($args[0]) {
				case "help":
					$this->commandManager->guild_execute("help", $sender, $args);
					break;
				case "create":
					$this->commandManager->guild_execute("create", $sender, $args);
					break;
				case "class":
					$this->commandManager->guild_execute("class", $sender, $args);
					break;
				case "disband":
					$this->commandManager->guild_execute("disband", $sender, $args);
					break;
				case "home":
					$this->commandManager->guild_execute("home", $sender, $args);
					break;
				case "sethome":
					$this->commandManager->guild_execute("sethome", $sender, $args);
					break;
				case "kick":
					$this->commandManager->guild_execute("kick", $sender, $args);
					break;
				case "leave":
					$this->commandManager->guild_execute("leave", $sender, $args);
					break;
				case "motto":
					$this->commandManager->guild_execute("motto", $sender, $args);
					break;
				case "vault":
					$this->commandManager->guild_execute("vault", $sender, $args);
					break;
				case "invite":
					$this->commandManager->guild_execute("invite", $sender, $args);
					break;
				default:
					if($sender instanceof KingdomsPlayer) {
						$sender->sendKingdomMessage("GUILD_COMMAND_USAGE");
					} else {
						$sender->sendMessage(TextFormat::RED . "* " . TextFormat::YELLOW . "If you don't know how to use this command, try using" . TextFormat::WHITE . " /k help");
					}
					break;
			}
		} else {
			if($sender instanceof KingdomsPlayer) {
				$sender->sendKingdomMessage("GUILD_COMMAND_USAGE");
			} else {
				$sender->sendMessage(TextFormat::RED . "* " . TextFormat::YELLOW . "If you don't know how to use this command, try using" . TextFormat::WHITE . " /k help");
			}
		}
	}

}