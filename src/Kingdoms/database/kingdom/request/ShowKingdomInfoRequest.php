<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 01/08/2016
 * Time: 18:43
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\Server;

class ShowKingdomInfoRequest extends MySQLRequest {

    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_ERROR = 1;
    const MYSQL_SUCCESS = 2;

    /** @var string */
    private $kingdom;

    /** @var string */
    private $player;

    /**
     * ShowKingdomInfoRequest constructor.
     *
     * @param KingdomDatabase $database
     * @param string $kingdom
     * @param string $player
     */
    public function __construct(KingdomDatabase $database, $kingdom, $player) {
        parent::__construct($database->getCredentials());
        $this->kingdom = strtoupper($kingdom);
        $this->player = $player;
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $result = $database->query("\nSELECT * FROM kingdoms WHERE name='{$this->kingdom}'");
            if($result instanceof \mysqli_result) {
                $row = $result->fetch_assoc();
                $result->free();
                $this->setResult([self::MYSQL_SUCCESS, $row]);
                // check top guild query and query to get all citizens
            }
            else {
                $this->setResult([self::MYSQL_ERROR]);
            }
        }
        $database->close();
    }

    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Main and $plugin->isEnabled()) {
            $player = $plugin->getServer()->getPlayerExact($this->player);
            if($player instanceof KingdomsPlayer) {
                $result = $this->getResult();
                switch($result[0]) {
                    case self::MYSQL_CONNECTION_ERROR:
                        $server->getLogger()->info("Couldn't execute ShowKingdomInfoRequest due connection error!");
                        break;
                    case self::MYSQL_ERROR:
                        $server->getLogger()->info("Couldn't execute ShowKingdomInfoRequest due error!");
                        break;
                    case self::MYSQL_SUCCESS:
                        $row = $result[1];
                        if(is_array($row)) {
                            $leader = (empty($row["leader"])) ? "No leader" : $row["leader"];
                            $player->sendKingdomInfo($row["name"], $row["motto"], $row["points"], $leader, $row["wonWars"]);
                        }
                        else {
                            $player->sendKingdomMessage("KINGDOM_INFO_FAILED_BY_KINGDOM");
                        }
                        $server->getLogger()->info("ShowKingdomInfoRequest was successfully executed by {$this->player}!");
                        break;
                }
            }
        }
    }

}