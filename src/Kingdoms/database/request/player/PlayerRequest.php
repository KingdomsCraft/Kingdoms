<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 11:01
 */

namespace Kingdoms\database\request\player;

use Kingdoms\Base;
use Kingdoms\database\request\Request;
use pocketmine\Player;
use pocketmine\Server;

abstract class PlayerRequest extends Request {

    /** @var string */
    protected $player;

    /**
     * PlayerRequest constructor.
     * @param array $credentials
     * @param string $player
     */
    public function __construct($credentials, $player) {
        parent::__construct($credentials);
        $this->player = strtolower($player);
    }

    /**
     * @param Server $server
     * @return null|Player
     */
    public function getPlayer(Server $server) {
        $plugin = $this->getPlugin($server);
        if($plugin instanceof Base) {
            $player = $plugin->getServer()->getPlayer($this->player);
            if($player instanceof Player) {
                return $player;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

}