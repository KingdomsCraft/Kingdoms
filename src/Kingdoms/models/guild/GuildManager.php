<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 22:55
 */

namespace Kingdoms\models\guild;

use Kingdoms\Base;
use Kingdoms\KingdomPlayer;

class GuildManager {

    /** @var Base */
    private $plugin;

    /** @var Guild[] */
    private $guilds = [];

    /**
     * GuildManager constructor.
     * @param Base $plugin
     */
    public function __construct(Base $plugin) {
        $this->plugin = $plugin;
        $this->init();
    }

    /**
     * Initialize Kingdom/Faction updater
     */
    public function init() {
        $this->plugin->getDatabaseManager()->initializeUpdater();
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
     * Return guild instance, null if not exists
     *
     * @param $guild
     * @return Guild|null
     */
    public function getGuild($guild) {
        if(isset($this->guilds[$guild])) {
            return $this->guilds[$guild];
        }
        else {
            $this->plugin->getLogger()->critical("Couldn't get kingdom {$guild} due it's not registered! Please, contact me (@AndrewBit4) to check what is happening!");
            return null;
        }
    }

    /**
     * @param Guild $guild
     * @return int|string
     */
    public function getGuildSafeName(Guild $guild) {
        $safeName = null;
        foreach($this->guilds as $name => $object) {
            if($guild == $object) {
                $safeName = $name;
                break;
            }
        }
        return $safeName;
    }

    /**
     * Register a new guild instance
     *
     * @param string $name
     * @param string $leader
     * @param string $motto
     * @param int $points
     * @param int $class
     * @param int $vault
     * @param string $home
     */
    public function loadGuild($name, $leader, $motto, $points, $class, $vault, $home) {
        if(!isset($this->guilds[$name])) {
            $this->guilds[$name] = new Guild($this->plugin, $name, $leader, $motto, $points, $class, $vault, $home);
        }
    }

    /**
     * Update guild name
     *
     * @param string $oldName
     * @param string $newName
     */
    public function updateName($oldName, $newName) {
        $logger = $this->plugin->getLogger();
        if(isset($this->guilds[$oldName])) {
            $kingdom = $this->guilds[$oldName];
            unset($this->guilds[$oldName]);
            $this->guilds[$newName] = $kingdom;
            $logger->info("{$oldName} guild was renamed to {$newName}");
        }
        else {
            $logger->critical("Couldn't rename {$oldName} to {$newName} due the guild is not loaded!");
        }
    }

    /**
     * Finalize guild
     *
     * @param Guild $guild
     */
    public function finalizeGuild(Guild $guild) {
        $name = $this->getGuildSafeName($guild);
        $this->plugin->getDatabaseManager()->updateGuild($name);
        $destroyInstance = true;
        /** @var KingdomPlayer $player */
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if($player->getGuild() == $guild) {
                $destroyInstance = false;
                break;
            }
        }
        $logger = $this->plugin->getLogger();
        if($destroyInstance) {
            if(isset($this->guilds[$name = $guild->getName()])) {
                unset($this->guilds[$name]);
                $logger->info("{$name} guild instance was finalized.");
            }
            else {
                $logger->critical("Couldn't finalize {$name} due we can't found a properly instance on GuildManager!");
            }
        }
    }

}