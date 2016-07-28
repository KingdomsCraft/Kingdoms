<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 20:25
 */

namespace Kingdoms\database\request\guild;

use Kingdoms\Base;
use pocketmine\Server;

class CreateGuildRequest extends GuildRequest {

    /** @var string */
    private $motto;

    /** @var string */
    private $leader;

    /**
     * CreateGuildRequest constructor.
     * @param array $credentials
     * @param string $guild
     * @param string $motto
     * @param string $leader
     */
    public function __construct($credentials, $guild, $motto, $leader) {
        parent::__construct($credentials, $guild);
        $this->motto = $motto;
        $this->leader = strtolower($leader);
    }

    public function onRun() {
        $database = $this->getDatabase();
        $result = $database->query("\nSELECT * FROM guilds WHERE name='{$this->guild}'");
        if(is_array($result->fetch_assoc())) {
            $this->setResult(false);
        }
        else {
            $this->setResult(true);
        }
        $database->close();
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Base) {
            $logger = $plugin->getLogger();
            if($this->getResult()) {
                $database = $this->getDatabase();
                $database->query("\nINSERT INTO guilds (name, leader, motto, points, vault, class, home) VALUES ('{$this->guild}', '{$this->leader}', '{$this->motto}', 0, 0, 0, '0')");
                $database->close();
                $logger->info("Guild {$this->guild} was successfully created!");
            }
            else {
                $logger->critical("Couldn't create guild {$this->guild}");
            }
        }
    }

}