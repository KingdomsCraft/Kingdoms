<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 8:56
 */

namespace Kingdoms\database\guild\request;

use Kingdoms\database\guild\GuildDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class InitDatabaseRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_ERROR = 1;
    const MYSQL_SUCCESS = 2;

    /**
     * InitDatabaseRequest constructor.
     *
     * @param GuildDatabase $database
     */
    public function __construct(GuildDatabase $database) {
        parent::__construct($database->getCredentials());
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $database->query("\nCREATE TABLE IF NOT EXISTS guilds (
            name VARCHAR(32) PRIMARY KEY,
            leader VARCHAR(128),
            motto VARCHAR(128) DEFAULT 'This is my amazing guild!',
            class INT DEFAULT 0,
            vault INT DEFAULT 0,
            points INT DEFAULT 0,
            home VARCHAR(128) DEFAULT '',
            kingdom VARCHAR(128))");
            if(isset($database->error) and $database->error) {
                $this->setResult([self::MYSQL_ERROR, $database->error]);
            }
            else {
                $this->setResult([self::MYSQL_SUCCESS]);
            }
        }
        $database->close();
    }

    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Main and $plugin->isEnabled()) {
            $result = $this->getResult();
            switch($result) {
                case self::MYSQL_CONNECTION_ERROR:
                    $server->getLogger()->info("Couldn't execute InitDatabaseRequest (Guilds) due connection error!");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_ERROR:
                    $server->getLogger()->info("Couldn't execute InitDatabaseRequest (Guilds) due {$result[1]} error");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_SUCCESS:
                    $server->getLogger()->info("InitDatabaseRequest (Guilds) was successfully executed!");
                    break;
            }
        }
    }

}