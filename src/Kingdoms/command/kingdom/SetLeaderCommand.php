<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 20:07
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class SetLeaderCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute setleader command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if($sender instanceof KingdomsPlayer) {
            if($sender->isAdmin()) {
                if(isset($args[0]) and !empty($args[0])) {
                    $player = $this->getPlugin()->getServer()->getPlayer($args[0]);
                    if($player instanceof KingdomsPlayer) {
                        $player->setKingdomRank(KingdomsPlayer::KINGDOM_RANK_KING);
                        if($player->gotKingdom()) {
                            $player->getKingdom()->setLeader($player->getName());
                        }
                    }
                    $this->getPlugin()->getPluginDatabase()->getPlayerDatabase()->setPlayerRank($args[0], KingdomsPlayer::KINGDOM_RANK_KING, true);
                    $sender->sendKingdomMessage("KINGDOM_SETLEADER_SUCCESS");
                }
                else {
                    $sender->sendKingdomMessage("KINGDOM_SETLEADER_USAGE");
                }
            }
            else {
                $sender->sendKingdomMessage("KINGDOM_SETLEADER_FAILED_BY_RANK");
            }
        }
        else {
            if(isset($args[0]) and !empty($args[0])) {
                $player = $this->getPlugin()->getServer()->getPlayer($args[0]);
                if($player instanceof KingdomsPlayer) {
                    $player->setKingdomRank(KingdomsPlayer::KINGDOM_RANK_KING);
                }
                $this->getPlugin()->getPluginDatabase()->getPlayerDatabase()->setPlayerRank($args[0], KingdomsPlayer::KINGDOM_RANK_KING, true);
                $sender->sendMessage("You set his rank successfully!");
            }
            else {
                $sender->sendMessage("Please, add a new parameter with the player name!");
            }
        }
    }

}