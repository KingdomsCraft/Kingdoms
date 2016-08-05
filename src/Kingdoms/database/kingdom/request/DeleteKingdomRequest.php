<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 18:39
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\Server;

class DeleteKingdomRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_ERROR = 1;
    const MYSQL_SUCCESS = 2;

    /** @var string */
    private $name;

    /**
     * DeleteKingdomRequest constructor.
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
            $name = $database->escape_string($this->name);
            $database->query("\nDELETE FROM kingdoms WHERE name = '{$name}'");
            $database->query("\nDELETE FROM guilds WHERE kingdom = '{$name}'");
            $database->query("\nUPDATE kingoms_players SET kingdom = '' WHERE kingdom = '{$name}'");
            if($database->affected_rows > 0 ) {
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
                    $server->getLogger()->info("Couldn't execute DeleteKingdomRequest with {$this->name} due connection error!");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_ERROR:
                    $server->getLogger()->info("Error while deleting kingdom {$this->name} on DeleteKingdomRequest!");
                    break;
                case self::MYSQL_SUCCESS:
                    /** @var KingdomsPlayer $player */
                    foreach($plugin->getKingdomManager()->getKingdom($this->name)->getPlayersByKingdom() as $player) {
                        $player->setKingdom(null);
                    }
                    $server->getLogger()->info("DeleteKingdomRequest was successfully executed with {$this->name} kingdom");
                    $server->reload();
                    break;
            }
        }
    }

}