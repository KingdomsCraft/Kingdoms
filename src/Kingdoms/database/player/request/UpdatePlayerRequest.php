<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 11:17
 */

namespace Kingdoms\database\player\request;

use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\database\player\PlayerDatabase;
use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\Server;

class UpdatePlayerRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_CONNECTION_SUCCESS = 1;

    /** @var string */
    private $name;

    /**
     * UpdatePlayerRequest constructor.
     *
     * @param PlayerDatabase $database
     * @param string $name
     */
    public function __construct(PlayerDatabase $database, $name) {
        parent::__construct($database->getCredentials());
        $this->name = strtolower($name);
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $this->setResult([self::MYSQL_CONNECTION_SUCCESS]);
        }
        $database->close();
    }

    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Main and $plugin->isEnabled()) {
            $player = $plugin->getServer()->getPlayerExact($this->name);
            if($player instanceof KingdomsPlayer) {
                $result = $this->getResult();
                switch($result[0]) {
                    case self::MYSQL_CONNECTION_ERROR:
                        $server->getLogger()->info("Couldn't exeecute UpdatePlayerRequest due connection error!");
                        throw new \RuntimeException($result[1]);
                        break;
                    case self::MYSQL_CONNECTION_SUCCESS:
                        $database = $this->getDatabase();
                        $kingdom = ($player->gotKingdom()) ? $player->getKingdom()->getName() : '';
                        $guild = ($player->gotGuild()) ? $player->getGuild()->getName() : '';
                        $admin = ($player->isAdmin()) ? 1 : 0;
                        $database->query("\nUPDATE kingdoms_players SET kingdom='{$database->escape_string($kingdom)}',kingdomRank={$player->getKingdomRank()},admin={$admin},guild='{$database->escape_string($guild)}' WHERE name='{$database->escape_string($this->name)}'");
                        if($database->affected_rows > 0) {
                            $server->getLogger()->info("UpdatePlayerRequest was successfully executed with {$this->name}");
                        }
                        else {
                            $server->getLogger()->info("UpdatePlayerRequest wasn't executed with {$this->name} (maybe nothing changed in his data!)");
                        }
                        $database->close();
                        break;
                }
            }
        }
    }
}