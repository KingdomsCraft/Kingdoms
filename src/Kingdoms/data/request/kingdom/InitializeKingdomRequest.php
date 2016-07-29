<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 19:17
 */

namespace Kingdoms\database\request\kingdom;

use Kingdoms\Base;
use pocketmine\Server;

class InitializeKingdomRequest extends KingdomRequest {

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_errno) {
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
                $result = $database->query("\nSELECT * FROM kingdoms");
                $kingdomManager = $plugin->getKingdomManager();
                while($resultArray = $result->fetch_assoc()) {
                    $kingdomManager->loadFaction($resultArray["name"], intval($resultArray["points"]), $resultArray["motto"], intval($resultArray["wonWars"]), intval($resultArray["lostWars"]), $resultArray["home"]);
                    $logger->info("Kingdom {$resultArray["name"]} was successfully loaded.");
                }
                $database->close();
                $logger->info("Kingdoms were successfully initialize");
            }
            else {
                $logger->critical("There were errors while kingdom initialization!");
            }
        }
    }

}