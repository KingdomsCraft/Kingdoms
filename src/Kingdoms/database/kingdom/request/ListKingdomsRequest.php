<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 31/07/2016
 * Time: 11:47
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\KingdomPlayer;
use Kingdoms\Main;
use pocketmine\Server;

class ListKingdomsRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_ERROR = 1;
    const MYSQL_SUCCESS = 2;

    /** @var string */
    private $name;

    /** @var string */
    private $page;

    /**
     * ListKingdomsRequest constructor.
     *
     * @param KingdomDatabase $database
     * @param string $name
     * @param int $page
     */
    public function __construct(KingdomDatabase $database, $name, $page) {
        parent::__construct($database->getCredentials());
        $this->name = $name;
        $this->page = $page;
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $result = $database->query("\n SELECT * FROM kingdoms");
            if($result instanceof \mysqli_result) {
                $result->free();
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
                    $server->getLogger()->info("Couldn't execute ListKingdomsRequest due connection error");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_ERROR:
                    $server->getLogger()->info("Couldn't execute ListKingdomsRequest due unknown error");
                    break;
                case self::MYSQL_SUCCESS:
                    $player = $plugin->getServer()->getPlayerExact($this->name);
                    if($player instanceof KingdomPlayer) {
                        $database = $this->getDatabase();
                        $result = $database->query("\nSELECT * FROM kingdoms");
                        $i = 0;
                        while($row = $result->fetch_assoc()) {
                            $i++;
                        }
                        $maxPages = floor($i / 5);
                        $result->free();
                        if($this->page < $maxPages) {
                            $player->sendKingdomMessage("KINGDOM_TOP_FAILED_REASON_PAGE");
                        }
                        else {
                            $player->sendPageAmount($this->page, $maxPages);
                            $result = $database->query("\nSELECT * FROM kingdoms");
                            $rank = 1 * $this->page;
                            while($row = $result->fetch_assoc()) {
                                $player->sendRankedKingdom($rank, $row["name"], $row["points"]);
                                $rank++;
                            }
                            $result->free();
                        }
                        $database->close();
                        $server->getLogger()->info("ListKingdomsRequest was successfully executed!");
                    }
                    break;
            }
        }
    }

}