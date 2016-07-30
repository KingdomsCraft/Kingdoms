<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 22:39
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomPlayer;

class HelpCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute help command
     *
     * @param KingdomPlayer $sender
     * @param array $args
     */
    public function execute(KingdomPlayer $sender, $args) {
        if(isset($args[0])) {
            $page = (int) $args[0];
            if(!$page > 0 and $page < 5) {
                $page = 1;
            }
        }
        else {
            $page = 1;
        }
        $sender->sendKingdomMessage("HELP_PAGE_{$page}");
    }

}