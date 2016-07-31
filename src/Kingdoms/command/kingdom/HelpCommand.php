<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 22:39
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\language\LanguageManager;

class HelpCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute help command
     *
     * @param KingdomsPlayer $sender
     * @param array $args
     */
    public function execute(KingdomsPlayer $sender, $args) {
        if(isset($args[0])) {
            $page = (int) $args[0];
            if($page <= 0) {
                $page = 1;
            }
            elseif($page > 5) {
                $page = 5;
            }
        }
        else {
            $page = 1;
        }
        $sender->sendMessage(str_replace("{page}", $page, LanguageManager::getInstance()->getMessage("HELP_HEADER")));
        $sender->sendKingdomMessage("HELP_PAGE_{$page}");
    }

}