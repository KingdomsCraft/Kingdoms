<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 8:55
 */

namespace Kingdoms\database\guild;

use Kingdoms\database\guild\request\InitDatabaseRequest;
use Kingdoms\database\guild\request\InitGuildRequest;
use Kingdoms\database\mysql\MySQLDatabase;

class GuildDatabase extends MySQLDatabase {

    /**
     * Initialize GuildDatabase
     */
    public function init() {
        $this->initDatabase();
    }

    /**
     * Initialize guild database
     */
    public function initDatabase() {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitDatabaseRequest($this));
    }

    /**
     * Initialize a guild
     *
     * @param string $name
     */
    public function initGuild($name) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitGuildRequest($this, $name));
    }

}