<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 23:29
 */

namespace Kingdoms\command;

use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class KingdomCommand extends Command {

	/** @var Main */
	private $plugin;

	/** @var CommandManager */
	private $commandManager;

	/**
	 * KingdomCommand constructor.
	 *
	 * @param CommandManager $commandManager
	 */
	public function __construct(CommandManager $commandManager) {
		$this->plugin = $commandManager->getPlugin();
		$this->commandManager = $commandManager;
		parent::__construct("kingdom", "Main kingdom command", "Usage: /k", ["k"]);
	}

	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(isset($args[0])) {
			switch(strtolower($args[0])) {
				case "help":
					$this->commandManager->kingdom_execute("help", $sender, $args);
					break;
				case "create":
					$this->commandManager->kingdom_execute("create", $sender, $args);
					break;
				case "top":
					$this->commandManager->kingdom_execute("top", $sender, $args);
					break;
				case "info":
					$this->commandManager->kingdom_execute("info", $sender, $args);
					break;
				case "home":
					$this->commandManager->kingdom_execute("home", $sender, $args);
					break;
				case "sethome":
					$this->commandManager->kingdom_execute("sethome", $sender, $args);
					break;
				case "list":
					$this->commandManager->kingdom_execute("list", $sender, $args);
					break;
				case "join":
					$this->commandManager->kingdom_execute("join", $sender, $args);
					break;
				case "leave":
					$this->commandManager->kingdom_execute("leave", $sender, $args);
					break;
				case "heal":
					$this->commandManager->kingdom_execute("heal", $sender, $args);
					break;
				case "broadcast":
					$this->commandManager->kingdom_execute("broadcast", $sender, $args);
					break;
				case "delete":
					$this->commandManager->kingdom_execute("delete", $sender, $args);
					break;
				case "motto":
					$this->commandManager->kingdom_execute("motto", $sender, $args);
					break;
				case "setcitizen":
					$this->commandManager->kingdom_execute("setcitizen", $sender, $args);
					break;
				case "setnobleman":
					$this->commandManager->kingdom_execute("setnobleman", $sender, $args);
					break;
				case "setleader":
					$this->commandManager->kingdom_execute("setleader", $sender, $args);
					break;
				case "setpoints":
					$this->commandManager->kingdom_execute("setpoints", $sender, $args);
					break;
				case "addpoints":
					$this->commandManager->kingdom_execute("addpoints", $sender, $args);
					break;
				case "addpointsbyplayer":
				case "addpointsbyplayername":
					$this->commandManager->kingdom_execute("addpointsbyplayername", $sender, $args);
					break;
				case "removepoints":
					$this->commandManager->kingdom_execute("removepoints", $sender, $args);
					break;
				default:
					if($sender instanceof KingdomsPlayer) {
						$sender->sendKingdomMessage("KINGDOM_COMMAND_USAGE");
					}
					break;
			}
		} else {
			if($sender instanceof KingdomsPlayer) {
				$sender->sendKingdomMessage("KINGDOM_COMMAND_USAGE");
			} else {
				$sender->sendMessage(TextFormat::RED . "* " . TextFormat::YELLOW . "If you don't know how to use this command, try using" . TextFormat::WHITE . " /k help");
			}
		}
	}

}