<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 23:29
 */

namespace Kingdoms\command;

use Kingdoms\KingdomPlayer;
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
        if($sender instanceof KingdomPlayer) {
            if(isset($args[0])) {
                switch(strtolower($args[0])) {
                    case "help":
                        $arguments = $args;
                        unset($args[0]);
                        $this->commandManager->kingdom_execute("help", $sender, $arguments);
                        break;
                    case "create":
                        $arguments = $args;
                        unset($args[0]);
                        $this->commandManager->kingdom_execute("create", $sender, $arguments);
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