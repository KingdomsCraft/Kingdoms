<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 01/08/2016
 * Time: 20:06
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class JoinCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute join command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if($sender instanceof KingdomsPlayer) {
            if(!$sender->gotKingdom()) {
                if(isset($args[0]) and !empty($args[0])) {
                    $kingdomManager = $this->getPlugin()->getKingdomManager();
                    if($kingdomManager->isKingdom($args[0])) {
                        $sender->setKingdomRank(0);
                        $sender->setKingdom($kingdomManager->getKingdom($args[0]));
                        $sender->sendKingdomMessage("KINGDOM_JOIN_SUCCESS");
                    }
                    else {
                        $sender->sendKingdomMessage("KINGDOM_JOIN_FAILED_BY_KINGDOM_EXISTS");
                    }
                }
                else {
                    $sender->sendKingdomMessage("KINGDOM_JOIN_USAGE");
                }
            }
            else {
                $sender->sendKingdomMessage("KINGDOM_JOIN_FAILED_BY_KINGDOM");
            }
        }
    }

}