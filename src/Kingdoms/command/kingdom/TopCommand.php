<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 31/07/2016
 * Time: 12:34
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;

class TopCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute top command
     *
     * @param KingdomsPlayer $sender
     * @param array $args
     */
    public function execute(KingdomsPlayer $sender, $args) {
        if(isset($args[0])) {
            $page = (int)$args[0];
            if(!$page > 0) {
                $page = 1;
            }
        }
        else {
            $page = 1;
        }
        $this->getPlugin()->getPluginDatabase()->getKingdomDatabase()->listKingdomList($sender->getName(), $page);
    }

}