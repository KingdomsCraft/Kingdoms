<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 11:26
 */

namespace Kingdoms\database\request\player;

use Kingdoms\Base;
use Kingdoms\KingdomPlayer;
use pocketmine\Server;

class UpdatePlayerRequest extends PlayerRequest {

    public function onRun() {
        $database = $this->getDatabase();
        $result = $database->query("\nSELECT * FROM kingdomsPlayers WHERE name='{$this->player}'");
        if((is_array($result->fetch_assoc()))) {
            $this->setResult(true);
        }
        else {
            $this->setResult(false);
        }
        $database->close();
    }

    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Base) {
            $player = $this->getPlayer($server);
            $logger = $plugin->getLogger();
            if($player instanceof KingdomPlayer) {
                if($this->getResult()) {
                    $database = $this->getDatabase();
                    $kingdom = ($player->gotKingdom()) ? $player->getKingdom()->getName() : '0';
                    $guild = ($player->gotGuild()) ? $player->getGuild()->getName() : '0';
                    $database->query("\nUPDATE kingdomsPlayers SET gotKingdom={$player->gotKingdom()},gotGuild={$player->getGuild()},guild='{$guild}',kingdom='{$kingdom}'");
                    $database->close();
                    $logger->info("{$this->player} player was updated");
                }
            }
            else {
                $logger->critical("Couldn't update {$this->player}! :(");
            }
        }
    }

}