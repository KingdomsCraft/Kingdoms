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
use Kingdoms\database\mysql\MySQLDatabase;

class KingdomDatabase extends MySQLDatabase {

    public function init() {
        $this->initDatabase();
        $this->initKingdoms();
    }

    public function initDatabase() {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitDatabaseRequest($this));
    }

    public function initKingdoms() {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitKingdomsRequest($this));
    }

}