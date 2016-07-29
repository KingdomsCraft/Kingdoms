<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 19:56
 */

namespace Kingdoms\models\guild;

use Kingdoms\Main;

class GuildManager {

    /** @var Main */
    private $plugin;

    /** @var array */
    private $guilds = [];

    /**
     * GuildManager constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * Return guilds
     *
     * @return Guild[]
     */
    public function getGuilds() {
        return $this->guilds;
    }

}