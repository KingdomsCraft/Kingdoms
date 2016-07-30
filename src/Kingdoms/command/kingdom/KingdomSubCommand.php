<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 22:38
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\KingdomPlayer;
use Kingdoms\Main;

interface KingdomSubCommand {

    /**
     * @param KingdomPlayer $sender
     * @param array $args
     */
    public function execute(KingdomPlayer $sender, $args);

}