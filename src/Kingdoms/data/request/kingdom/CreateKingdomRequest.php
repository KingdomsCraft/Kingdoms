<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 16:16
 */

namespace Kingdoms\database\request\kingdom;

use Kingdoms\Base;
use pocketmine\Server;

class CreateKingdomRequest extends KingdomRequest {

    /** @var string */
    private $motto;

    /**
     * CreateKingdomRequest constructor.
     * @param array $credentials
     * @param string $kingdom
     * @param string $motto
     */
    public function __construct($credentials, $kingdom, $motto) {
        parent::__construct($credentials, $kingdom);
        $this->motto = $motto;
    }

    public function onRun() {
        $database = $this->getDatabase();
        $result = $database->query("\nSELECT * FROM kingdoms WHERE name='{$this->kingdom}'");
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
                $database->query("\nINSERT INTO kingdoms (name, motto, points, wonWars, lostWars, home) VALUES ('{$this->kingdom}','{$this->motto}',0,0,0,'0')");
                $database->close();
                $logger->info("{$this->kingdom} was successfully created!");
            }
            else {
                $logger->info("Couldn't create {$this->kingdom} kingdom");
            }
        }
    }

}