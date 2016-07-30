<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 21:19
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class InitKingdomRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const KINGDOM_NO_REGISTERED = 1;
    const MYSQL_SUCCESS = 2;

    /** @var string */
    private $name;

    /**
     * InitKingdomRequest constructor.
     *
     * @param KingdomDatabase $database
     * @param string $name
     */
    public function __construct(KingdomDatabase $database, $name) {
        parent::__construct($database->getCredentials());
        $this->name = strtoupper($name);
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $result = $database->query("\nSELECT * FROM kingdoms WHERE name='$this->name'");
            if($result instanceof \mysqli_result) {
                $row = $result->fetch_assoc();
                $result->free();
                if(is_array($row)) {
                    $this->setResult([self::MYSQL_SUCCESS, $row]);
                }
            }
            else {
                $this->setResult([self::KINGDOM_NO_REGISTERED]);
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
                    $server->getLogger()->debug("Couldn't execute InitKingdomRequest due MySQL connection error");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::KINGDOM_NO_REGISTERED:
                    $server->getLogger()->debug("Couldn't execute InitKingdomRequest due kingdom {$this->name} is not registered!");
                    break;
                case self::MYSQL_SUCCESS:
                    $kingdom = $plugin->getKingdomManager()->getKingdom($this->name);
                    // ToDo: init kingdom
                    break;
            }
        }
    }

}