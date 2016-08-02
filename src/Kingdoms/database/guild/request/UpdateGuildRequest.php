<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 16:23
 */

namespace Kingdoms\database\guild\request;

use Kingdoms\database\guild\GuildDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class UpdateGuildRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_NOT_UPDATED = 1;
    const MYSQL_UPDATED = 2;

    /** @var string */
    private $name;

    /** @var string */
    private $leader;

    /** @var string */
    private $motto;

    /** @var string */
    private $class;

    /** @var string */
    private $vault;

    /** @var string */
    private $points;

    /** @var string */
    private $home;

    /** @var string */
    private $kingdom;

    /**
     * UpdateGuildRequest constructor.
     *
     * @param GuildDatabase $database
     * @param array $data
     */
    public function __construct(GuildDatabase $database, $data) {
        parent::__construct($database->getCredentials());
        $this->name = $data["name"];
        $this->leader = $data["leader"];
        $this->motto = $data["motto"];
        $this->class = (int) $data["class"];
        $this->vault = (int) $data["vault"];
        $this->points = (int) $data["points"];
        $this->home = $data["home"];
        $this->kingdom = $database["kingdom"];
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            $database->query("\nUPDATE guilds SET name='{$database->escape_string($this->name)}',leader='{$database->escape_string($this->leader)}',motto='{$database->escape_string($this->motto)}',class={$this->class},vault={$this->vault},points={$this->points},home='{$database->escape_string($this->home)}',kingdom='{$database->escape_string($this->kingdom)} WHERE name='{$database->escape_string($this->name)}'");
            if($database->affected_rows > 0) {
                $this->setResult([self::MYSQL_UPDATED]);
            }
            else {
                $this->setResult([self::MYSQL_NOT_UPDATED]);
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
                    $server->getLogger()->info("Couldn't execute UpdateGuildRequest due connection error!");
                    throw new \RuntimeException($result[1]);
                    break;
                case self::MYSQL_NOT_UPDATED:
                    $server->getLogger()->info("{$this->name} guild wasn't updated in UpdateGuildRequest");
                    break;
                case self::MYSQL_UPDATED:
                    $server->getLogger()->info("UpdateGuildRequest was successfully executed with {$this->name} guild!");
                    break;
            }
        }
    }

}