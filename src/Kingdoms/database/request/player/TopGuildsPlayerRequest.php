<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 26/07/2016
 * Time: 21:58
 */

namespace Kingdoms\database\request\player;

use Kingdoms\Base;
use Kingdoms\KingdomPlayer;
use pocketmine\Server;

class TopGuildsPlayerRequest extends PlayerRequest {

    /** @var string */
    private $kingdom;

    /** @var string */
    private $page;

    /**
     * TopGuildsPlayerRequest constructor.
     * @param array $credentials
     * @param string $player
     * @param $kingdom
     * @param $page
     */
    public function __construct($credentials, $player, $kingdom, $page = 1) {
        parent::__construct($credentials, $player);
        $this->kingdom = strtolower($kingdom);
        $this->page = (int)$page;
    }

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
            $logger = $plugin->getLogger();
            $player = $plugin->getServer()->getPlayer($this->player);
            if($player instanceof KingdomPlayer) {
                if($this->getResult()) {
                    $database = $this->getDatabase();
                    $toShow = 5 * $this->page;
                    $result = $database->query("\nSELECT * FROM guilds WHERE kingdom='{$this->kingdom}' ORDER BY id DESC LIMIT {$toShow}");
                    $rank = 5;
                    while($resultArray = $result->fetch_assoc()) {
                        $top = $toShow - $rank;
                        $player->sendMessage("{$top}: {$result["name"]} - {$result["points"]} points");
                        $rank--;
                    }
                    $database->close();
                }
                else {
                    $logger->critical("{$this->kingdom} isn't a valid one");
                }
            }
            else {
                $logger->critical("{$this->player} is not a valid player! issue#3");
            }
        }
    }

}