<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 22:38
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\KingdomsPlayer;

interface KingdomSubCommand {

    /**
     * @param KingdomsPlayer $sender
     * @param array $args
     */
    public function execute(KingdomsPlayer $sender, $args);

}