<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 23/07/2016
 * Time: 17:30
 */

namespace Kingdoms;

use Kingdoms\command\CommandManager;
use Kingdoms\database\DatabaseManager;
use Kingdoms\models\guild\GuildManager;
use Kingdoms\models\kingdom\KingdomManager;
use Kingdoms\models\LanguageManager;
use pocketmine\plugin\PluginBase;

class Base extends PluginBase {

    /** @var Base */
    private static $object = null;

    /** @var Events */
    private $listener;

    /** @var LanguageManager */
    private $languageManager;

    /** @var DatabaseManager */
    private $databaseManager;

    /** @var KingdomManager */
    private $kingdomManager;

    /** @var GuildManager */
    private $guildManager;

    /** @var CommandManager */
    private $commandManager;

    public function onLoad() {
        if(!self::$object instanceof Base) {
            self::$object = $this;
        }
    }

    public function onEnable() {
        $this->initialize();
        $this->setLanguageManager();
        $this->setDatabaseManager();
        $this->setKingdomManager();
        $this->setGuildManager();
        $this->setCommandManager();
        $this->setListener();
        $this->getLogger()->info("Kingdoms was enabled.");
    }

    public function onDisable() {
        $this->getLogger()->info("Kingdoms was disabled.");
    }

    /**
     * @return Base
     */
    public static function getInstance() {
        return self::$object;
    }

    /**
     * Return Events instance
     *
     * @return Events
     */
    public function getListener() {
        return $this->listener;
    }

    /**
     * Return LanguageManager instance
     *
     * @return LanguageManager
     */
    public function getLanguageManager() {
        return $this->languageManager;
    }

    /**
     * Return DatabaseManager instance
     *
     * @return DatabaseManager
     */
    public function getDatabaseManager() {
        return $this->databaseManager;
    }

    /**
     * Return KingdomManager instance
     *
     * @return KingdomManager
     */
    public function getKingdomManager() {
        return $this->kingdomManager;
    }

    /**
     * Return GuildManager instance
     *
     * @return GuildManager
     */
    public function getGuildManager() {
        return $this->guildManager;
    }

    /**
     * Return CommandManager instance
     *
     * @return CommandManager
     */
    public function getCommandManager() {
        return $this->commandManager;
    }

    /**
     * Register Events instance
     */
    public function setListener() {
        $this->listener = new Events($this);
    }

    /**
     * Register LanguageManager instance
     */
    public function setLanguageManager() {
        $this->languageManager = new LanguageManager($this, $this->getConfig()->get("default-language"));
    }

    /**
     * Register DatabaseManager instance
     */
    public function setDatabaseManager() {
        $this->databaseManager = new DatabaseManager($this);
    }

    /**
     * Register KingdomManager instance
     */
    public function setKingdomManager() {
        $this->kingdomManager = new KingdomManager($this);
    }

    /**
     * Register GuildManager instance
     */
    public function setGuildManager() {
        $this->guildManager = new GuildManager($this);
    }

    /**
     * Register CommandManager instance
     */
    public function setCommandManager() {
        $this->commandManager = new CommandManager($this);
    }

    /**
     * Do all folders and resources if they don't exists
     */
    public function initialize() {
        if(!is_dir($this->getDataFolder())) mkdir($this->getDataFolder());
        if(!is_dir($path = $this->getDataFolder() . "messages")) mkdir($path);
        $this->saveResource("messages/english.json");
        $this->saveDefaultConfig();
    }

}