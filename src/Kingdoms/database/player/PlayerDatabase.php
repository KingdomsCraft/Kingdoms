<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 9:43
 */

namespace Kingdoms\database\player;

use Kingdoms\database\mysql\MySQLDatabase;
use Kingdoms\database\player\request\InitDatabaseRequest;
use Kingdoms\database\player\request\LoginPlayerRequest;
use Kingdoms\database\player\request\RegisterPlayerRequest;
use Kingdoms\database\player\request\SetRankPlayerRequest;
use Kingdoms\database\player\request\UpdatePlayerRequest;

class PlayerDatabase extends MySQLDatabase {

    /**
     * Initialize PlayerDatabase
     */
    public function init() {
        $this->initDatabase();
    }

    /**
     * Initialize player database
     */
    public function initDatabase() {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitDatabaseRequest($this));
    }

    /**
     * Initialize a player
     *
     * @param string $name
     */
    public function loginPlayer($name) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new LoginPlayerRequest($this, $name));
    }

    /**
     * Register a player
     *
     * @param string $name
     */
    public function registerPlayer($name) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new RegisterPlayerRequest($this, $name));
    }

    /**
     * Update a player
     *
     * @param string $name
     */
    public function updatePlayer($name) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new UpdatePlayerRequest($this, $name));
    }

    /**
     * Set a player rank
     *
     * @param string $player
     * @param $rank
     * @param bool $delete
     */
    public function setPlayerRank($player, $rank, $delete = false) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new SetRankPlayerRequest($this, $player, $rank, $delete));
    }
}