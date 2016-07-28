<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 10:22
 */

namespace Kingdoms\database\request\guild;

use Kingdoms\Base;
use Kingdoms\models\guild\Guild;
use pocketmine\Server;

class UpdateGuildRequest extends GuildRequest {

    public function onRun() {
        $database = $this->getDatabase();
        $result = $database->query("\nSELECT * FROM guilds WHERE name='{$this->guild}'");
        if(is_array($result->fetch_assoc())) {
            $this->setResult(true);
        }
        else {
            $this->setResult(false);
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
                $result = $database->query("\nSELECT * FROM guilds WHERE name='{$this->guild}'");
                if(is_array($result = $result->fetch_assoc())) {
                    $guild = $plugin->getGuildManager()->getGuild($this->guild);
                    if($guild instanceof Guild) {
                        $database->query("\nUPDATE guilds SET name='{$guild->getName()}',class={$guild->getClass()},home='{$guild->getHome()}',vault={$guild->getRubies()},points={$guild->getPoints()},motto='{$guild->getMotto()}' WHERE name='{$this->guild}'");
                        $logger->info("{$this->guild} guild was updated.");
                    }
                    else {
                        $logger->info("Couldn't update {$this->guild} due unknown error (2)");
                    }
                }
                else {
                    $logger->critical("Couldn't update {$this->guild} due unknown error");
                }
                $database->close();
            }
            else {
                $logger->critical("Couldn't load {$this->guild} guild due it is not registered on the database!");
            }
        }
    }

}