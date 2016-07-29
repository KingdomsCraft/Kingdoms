<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 9:58
 */

namespace Kingdoms\database\request\guild;

use Kingdoms\Base;
use pocketmine\Server;

class InitializeGuildRequest extends GuildRequest {

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
                $result = $result->fetch_assoc();
                if(is_array($result)) {
                    $plugin->getGuildManager()->loadGuild($result["name"], $result["leader"], $result["motto"], intval($result["points"]), intval($result["class"]), intval($result["vault"]), $result["home"]);
                    $logger->info("Guild {$this->guild} was successfully initialized");
                }
                else {
                    $logger->critical("Something happen while initializing {$this->guild}! Contact the developer to check what is happening!");
                }
                $database->close();
            }
            else {
                $logger->critical("Couldn't initialize guild {$this->guild}! (Maybe it's not a guild?)");
            }
        }
    }

}