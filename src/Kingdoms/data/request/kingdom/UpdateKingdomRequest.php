<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 21:46
 */

namespace Kingdoms\database\request\kingdom;

use Kingdoms\Base;
use Kingdoms\models\kingdom\Kingdom;
use pocketmine\Server;

class UpdateKingdomRequest extends KingdomRequest {

    public function onRun() {
        $database = $this->getDatabase();
        $result = $database->query("\nSELECT * FROM kingdoms WHERE name='{$this->kingdom}'");
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
            if($this->getResult()) {
                $kingdom = $plugin->getKingdomManager()->getKingdom($this->kingdom);
                if($kingdom instanceof Kingdom) {
                    $database = $this->getDatabase();
                    $database->query("\nUPDATE kingdoms SET name='{$kingdom->getName()}',points={$kingdom->getPoints()},home='{$kingdom->getHome()}',wonWars={$kingdom->getWonWars()},lostWars={$kingdom->getLostWars()},motto='{$kingdom->getMotto()}' WHERE name='{$this->kingdom}'");
                    $plugin->getLogger()->info("Kingdom {$this->kingdom} was updated successfully");
                    $database->close();
                }
                else {
                    $plugin->getLogger()->info("Couldn't update {$this->kingdom} due it hasn't a properly object!");
                }
            }
            else {
                $plugin->getLogger()->critical("Couldn't update {$this->kingdom}! (Maybe it doesn't exists?)");
            }
        }
    }

}