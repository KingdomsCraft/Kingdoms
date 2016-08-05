<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 20:20
 */

namespace Kingdoms\database\player\request;

use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\database\player\PlayerDatabase;
use Kingdoms\Main;
use pocketmine\Server;

class SetRankPlayerRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_ERROR = 1;
    const MYSQL_SUCCESS = 2;

    /** @var string */
    private $name;

    /** @var int */
    private $rank;

    /** @var bool */
    private $delete;

    /**
     * SetRankPlayerRequest constructor.
     *
     * @param PlayerDatabase $database
     * @param string $name
     * @param $rank
     * @param bool $delete
     */
    public function __construct(PlayerDatabase $database, $name, $rank, $delete = false) {
        parent::__construct($database->getCredentials());
        $this->name = $name;
        $this->rank = (int) $rank;
        $this->delete = $delete;
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $name = $database->escape_string($this->name);
            $database->query("\nUPDATE kingdoms_players SET kingdomRank='{$this->rank}' WHERE name='{$name}'");
            if($this->delete) {
                $database->query("\nUPDATE kingdoms_players SET kingdomRank='0' WHERE kingdomRank={$this->rank}");
            }
            $result = $database->query("\nSELECT kingdom FROM kingdoms_players WHERE name='{$name}'");
            if($result instanceof \mysqli_result) {
                if(is_array($row = $result->fetch_assoc())) {
                    $database->query("\nUPDATE kingdoms SET leader='{$name}' WHERE name='{$row["kingdom"]}'");
                }
            }
            $result->free();
            if($database->affected_rows > 0) {
                $this->setResult([self::MYSQL_SUCCESS]);
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
            $result = $this->getResult();
            switch($result[0]) {
                case self::MYSQL_CONNECTION_ERROR:
                    $server->getLogger()->info("Couldn't connect to the database due connection error!");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_ERROR:
                    $server->getLogger()->info("SetRankPlayerRequest failed due MySQL error");
                    break;
                case self::MYSQL_SUCCESS:
                    $server->getLogger()->info("SetRankPlayerRequest was successfully executed!");
                    break;
            }
        }
    }

}