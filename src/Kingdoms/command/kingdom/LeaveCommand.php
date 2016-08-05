<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 14:10
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use kingdomscraft\economy\Economy;
use pocketmine\command\CommandSender;

class LeaveCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute leave command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if($sender instanceof KingdomsPlayer) {
            if($sender->gotKingdom()) {
                $config = $this->getPlugin()->getConfig()->getAll();
                if($sender->isAdmin() or Economy::getInstance()->getGold($sender) >= (int)$config["leave-price"]) {
                    Economy::getInstance()->removeGold($sender);
                    $sender->setKingdomRank(0);
                    $sender->sendKingdomMessage("LEAVE_SUCCESS");
                }
                else {
                    $sender->sendKingdomMessage("LEAVE_FAILED_BY_MONEY");
                }
            }
            else {
                $sender->sendKingdomMessage("LEAVE_FAILED_BY_KINGDOM");
            }
        }
    }

}