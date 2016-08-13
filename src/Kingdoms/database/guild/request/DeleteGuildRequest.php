<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 12/08/2016
 * Time: 10:53
 */

namespace Kingdoms\database\guild\request;

use Kingdoms\database\guild\GuildDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class DeleteGuildRequest extends MySQLRequest {

    const CONNECTION_ERROR = 0;
    const DELETE_FAILED = 1;
    const DELETE_SUCCESS = 2;

    /** @var string */
    private $name;

    /**
     * DeleteGuildRequest constructor.
     *
     * @param GuildDatabase $database
     * @param string $name
     */
    public function __construct(GuildDatabase $database, $name) {
        parent::__construct($database->getCredentials());
        $this->name = strtoupper($name);
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $database->query("DELETE FROM guilds WHERE name='{$database->escape_string($this->name)}'");
            $database->query("UPDATE kingdoms_players SET guild='' and leader='0' WHERE guild='{$database->escape_string($this->name)}'");
            if($database->affected_rows > 0) {
                $this->setResult(self::DELETE_SUCCESS);
            }
            else {
                $this->setResult(self::DELETE_FAILED);
            }
        }
        $database->close();
    }

    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Main and $plugin->isEnabled()) {
            $result = $this->getResult();
            switch(is_array($result) ? $result[0] : $result) {
                case self::CONNECTION_ERROR:
                    $server->getLogger()->info("DeleteGuildRequest failed due connection error");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::DELETE_FAILED:
                    $server->getLogger()->info("DeleteGuildRequest ({$this->name}) failed due unknown error!");
                    break;
                case self::DELETE_SUCCESS:
                    $server->getLogger()->info("DeleteGuildRequest was successfully executed");
                    break;
            }
        }
    }

}