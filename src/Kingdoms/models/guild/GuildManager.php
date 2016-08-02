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
     * Return if a guild is registered
     *
     * @param string $name
     * @return bool
     */
    public function isGuild($name) {
        return isset($this->guilds[$name]);
    }

    /**
     * Return guilds
     *
     * @return Guild[]
     */
    public function getGuilds() {
        return $this->guilds;
    }

    /**
     * Return guild object
     *
     * @param string $name
     * @return null
     */
    public function getGuild($name) {
        return (isset($this->guilds[$name])) ? $this->guilds[$name] : null;
    }

    /**
     * Register a guild
     *
     * @param string $name
     * @param string $leader
     * @param string $motto
     * @param $points
     * @param $class
     * @param $vault
     * @param string $home
     * @param string $kingdom
     */
    public function registerGuild($name, $leader, $motto, $points, $class, $vault, $home, $kingdom) {
        $this->guilds[$name] = new Guild($this->plugin, $leader, $name, $motto, $points, $class, $vault, $home, $kingdom);
    }

}