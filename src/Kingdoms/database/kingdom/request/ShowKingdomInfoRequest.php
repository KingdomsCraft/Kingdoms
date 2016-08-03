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
            $name = $database->escape_string($this->kingdom);
            $result = $database->query("\nSELECT * FROM kingdoms WHERE name='{$name}'");
            $database->query("SET @rownum := 0");
            $result2 = $database->query("\nSELECT * FROM ( SELECT name, points, @rownum := @rownum + 1 AS rank FROM kingdoms ORDER BY points DESC ) as result WHERE name='{$name}'");
            $result3 = $database->query("\nSELECT COUNT(*) as citizens FROM kingdoms_players WHERE kingdom='{$name}' and kingdomRank=0");
            if($result instanceof \mysqli_result and $result2 instanceof \mysqli_result and $result3 instanceof \mysqli_result) {
                $row = $result->fetch_assoc();
                $row2 = $result2->fetch_assoc();
                $row3 = $result3->fetch_assoc();
                $result->free();
                $this->setResult([self::MYSQL_SUCCESS, $row, $row2, $row3]);
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
                        if(is_array($row) and is_array($result[2]) and is_array($result[3])) {
                            $leader = (empty($row["leader"])) ? "No leader" : $row["leader"];
                            $player->sendKingdomInfo($row["name"], $row["motto"], $row["points"], $leader, $row["wonWars"], $result[2]["rank"], $result[3]["citizens"]);
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