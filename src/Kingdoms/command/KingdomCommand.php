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
                default:
                    if($sender instanceof KingdomsPlayer) {
                        $sender->sendKingdomMessage("KINGDOM_COMMAND_USAGE");
                    }
                    break;
            }
        }
        else {
            if($sender instanceof KingdomsPlayer) {
                $sender->sendKingdomMessage("KINGDOM_COMMAND_USAGE");
            }
        }
    }

}