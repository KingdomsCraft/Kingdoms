<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 19:13
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class DeleteCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute delete command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if($sender instanceof KingdomsPlayer) {
            if($sender->isAdmin()) {
                if(isset($args[0]) and !empty($args[0])) {
                    if($this->getPlugin()->getKingdomManager()->isKingdom($args[0])) {
                        $this->getPlugin()->getKingdomManager()->deleteKingdom($args[0]);
                        $sender->sendKingdomMessage("KINGDOM_DELETE_SUCCESS");
                    }
                    else {
                        $sender->sendKingdomMessage("KINGDOM_DELETE_FAILED_BY_KINGDOM");
                    }
                }
                else {
                    $sender->sendKingdomMessage("KINGDOM_DELETE_USAGE");
                }
            }
        }
    }

}