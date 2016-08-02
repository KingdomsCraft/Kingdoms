<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 01/08/2016
 * Time: 19:56
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;

class SetHomeCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute sethome command
     *
     * @param KingdomsPlayer $sender
     * @param array $args
     */
    public function execute(KingdomsPlayer $sender, $args) {
        if($sender->isAdmin()) {
            if($sender->gotKingdom()) {
                $sender->getKingdom()->setHomePosition($sender->getPosition());
                $sender->sendKingdomMessage("KINGDOM_SET_SUCCESS");
            }
            else {
                $sender->sendKingdomMessage("KINGDOM_SETHOME_FAILED_BY_KINGDOM");
            }
        }
        else {
            $sender->sendKingdomMessage("KINGDOM_SETHOME_FAILED_BY_PERM");
        }
    }

}