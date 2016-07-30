<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 22:03
 */

namespace Kingdoms\database\kingdom;

use Kingdoms\database\kingdom\request\InitDatabaseRequest;
use Kingdoms\database\kingdom\request\InitKingdomsRequest;
use Kingdoms\database\kingdom\request\UpdateKingdomRequest;
use Kingdoms\database\mysql\MySQLDatabase;
use Kingdoms\models\kingdom\Kingdom;

class KingdomDatabase extends MySQLDatabase {

    /**
     * Initialize the class
     */
    public function init() {
        $this->initDatabase();
        $this->initKingdoms();
    }

    /**
     * Initialize the database
     */
    public function initDatabase() {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitDatabaseRequest($this));
    }

    /**
     * Initialize all kingdoms
     */
    public function initKingdoms() {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitKingdomsRequest($this));
    }

    /**
     * Update a kingdom
     *
     * @param Kingdom $kingdom
     */
    public function updateKingdom(Kingdom $kingdom) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new UpdateKingdomRequest($this, $kingdom->getData()));
    }

}