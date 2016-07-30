<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 15:19
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class InitKingdomsRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const KINGDOMS_NOT_FOUND = 1;
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
            $result = $database->query("\nSELECT * FROM kingdoms");
            if($result instanceof \mysqli_result) {
                $this->setResult([self::MYSQL_SUCCESS, $result]);
            }
            else {
                $this->setResult([self::KINGDOMS_NOT_FOUND]);
            }
        }
        $database->close();
    }

    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Main and $plugin->isEnabled()) {
            $result = $this->getResult();
            switch($result[0]) {
                case self::MYSQL_CONNECTION_ERROR:
                    $server->getLogger()->debug("Couldn't execute InitKingdomsRequest due connection error!");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_SUCCESS:
                    /** @var \mysqli_result $result */
                    $result = $result[1];
                    $kingdomManager = $plugin->getKingdomManager();
                    while($row = $result->fetch_assoc()) {
                        $kingdomManager->registerKingdom($result["name"], $result["points"], $result["motto"], $result["lostWars"], $result["wonWars"], $result["home"]);
                    }
                    $result->free();
                    $server->getLogger()->info("InitKingdomsRequest was successfully executed!");
                    break;
                case self::KINGDOMS_NOT_FOUND:
                    $server->getLogger()->debug("Couldn't execute InitKingdomsRequest due kingdoms not found!");
                    break;
            }
        }
    }

}