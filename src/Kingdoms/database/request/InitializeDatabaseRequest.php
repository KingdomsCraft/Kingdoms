<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 19:19
 */

namespace Kingdoms\database\request;

use Kingdoms\Base;
use pocketmine\Server;

class InitializeDatabaseRequest extends Request {

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult(false);
        }
        else {
            $database->query("\nCREATE TABLE IF NOT EXISTS kingdoms (
            name VARCHAR(32) PRIMARY KEY,
            motto VARCHAR(128),
            points INT,
            wonWars INT,
            lostWars INT,
            home VARCHAR(128))");
            $database->query("\nCREATE TABLE IF NOT EXISTS kingdomsPlayers (
            name VARCHAR(32) PRIMARY KEY,
            gotKingdom BYTE,
            kingdom VARCHAR(32),
            kingdomRank INT,
            gotGuild BYTE,
            guild VARCHAR(32)
            )");
            $database->query("\nCREATE TABLE IF NOT EXISTS guilds (
            name VARCHAR(32) PRIMARY KEY,
            leader VARCHAR(32),
            motto VARCHAR(128),
            points INT,
            vault INT,
            class TINYINT DEFAULT,
            home VARCHAR(128))");
            $this->setResult(true);
        }
        $database->close();
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Base) {
            $logger = $plugin->getLogger();
            if($this->getResult()) {
                $logger->info("Database was initialized successfully.");
            }
            else {
                $logger->critical("Couldn't initialize database!");
            }
        }
    }

}