<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 11:11
 */

namespace Kingdoms\database\request\player;

use Kingdoms\Base;
use Kingdoms\KingdomPlayer;
use pocketmine\Server;

class InitializePlayerRequest extends PlayerRequest {

    public function onRun() {
        $database = $this->getDatabase();
        $result = $database->query("\nSELECT * FROM kingdomsPlayers WHERE name='{$this->player}'");
        if(is_array($result->fetch_assoc())) {
            $this->setResult(true);
        }
        else {
            $this->setResult(false);
        }
        $database->close();
    }

    public function onCompletion(Server $server) {
        $player = $this->getPlayer($server);
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Base) {
            $database = $this->getDatabase();
            $logger = $plugin->getLogger();
            if($player instanceof KingdomPlayer) {
                if($this->getResult()) {
                    $result = $database->query("\nSELECT * FROM kingdomsPlayers WHERE name='{$this->player}'");
                    $player->setKingdom((intval($result["gotKingdom"]) == 1) ? $plugin->getKingdomManager()->getKingdom($result["kingdom"]) : false);
                    $player->setGuild(intval($result["gotGuild"]) == 1) ? $result["guild"] : false ;
                }
                else {
                    $database->query("\nINSERT INTO kingdomsPlayers (name, gotKingdom, kingdom, kingdomRank, gotGuild, guild) VALUES ('{$this->player}', 0, '0', 0, 0, '0')");
                }
            }
            else {
                $logger->critical("Couldn't load player {$this->player}! Unknown error");
            }
            $database->close();
        }
    }

}