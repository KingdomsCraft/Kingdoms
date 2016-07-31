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
        if($sender instanceof KingdomsPlayer) {
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
                    default:
                        $sender->sendKingdomMessage("KINGDOM_COMMAND_USAGE");
                        break;
                }
            }
            else {
                $sender->sendKingdomMessage("KINGDOM_COMMAND_USAGE");
            }
        }
        else {
            $sender->sendMessage("Please, run this command in-game.");
        }
    }

}