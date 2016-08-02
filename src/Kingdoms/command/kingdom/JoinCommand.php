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

class JoinCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute join command
     *
     * @param KingdomsPlayer $sender
     * @param array $args
     */
    public function execute(KingdomsPlayer $sender, $args) {
        if(!$sender->gotKingdom()) {
            if(isset($args[0])) {
                $kingdomManager = $this->getPlugin()->getKingdomManager();
                if($kingdomManager->isKingdom($args[0])) {
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