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
use pocketmine\command\CommandSender;

class HelpCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute help command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if($sender instanceof KingdomsPlayer) {
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

}