<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 22:05
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class InitDatabaseRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_ERROR = 1;
    const MYSQL_SUCCESS = 2;

    /**
     * InitKingdomsRequest constructor.
     *
     * @param KingdomDatabase $database
     */
    public function __construct(KingdomDatabase $database) {
        parent::__construct($database->getCredentials());
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $database->query("\nCREATE TABLE IF NOT EXISTS kingdoms (
            name VARCHAR(32) PRIMARY KEY,
            motto VARCHAR(128),
            points INT,
            wonWars INT,
            lostWars INT,
            home VARCHAR(128))");
            if(isset($database->error) and $database->error) {
                $this->setResult([self::MYSQL_ERROR, $database->error]);
            }
            else {
                $this->setResult([self::MYSQL_SUCCESS]);
            }
        }
        $database->close();
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Main and $plugin->isEnabled()) {
            $result = $this->getResult();
            switch($result[0]) {
                case self::MYSQL_CONNECTION_ERROR:
                    $server->getLogger()->debug("Couldn't execute InitDatabaseRequest due MySQL connection error");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_ERROR:
                    $server->getLogger()->debug("Couldn't execute InitDatabaseRequest due database error");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_SUCCESS:
                    $server->getLogger()->debug("InitKingdomsRequest successfully done");
                    break;
            }
        }
    }

}