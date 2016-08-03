<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 01/08/2016
 * Time: 19:45
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;

class HomeCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute home command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if($sender instanceof KingdomsPlayer) {
            if($sender->gotKingdom()) {
                $home = $sender->getKingdom()->getHomePosition();
                if($home instanceof Position) {
                    $sender->teleport($home);
                    $sender->sendKingdomMessage("KINGDOM_HOME_SUCCESS");
                }
                else {
                    $sender->sendKingdomMessage("KINGDOM_HOME_FAILED_BY_HOME");
                }
            }
            else {
                $sender->sendKingdomMessage("KINGDOM_HOME_FAILED_BY_KINGDOM");
            }
        }
    }

}