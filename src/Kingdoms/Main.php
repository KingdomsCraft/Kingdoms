<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 17:08
 */

namespace Kingdoms;

use Kingdoms\command\CommandManager;
use Kingdoms\database\PluginDatabase;
use Kingdoms\language\LanguageManager;
use Kingdoms\models\guild\GuildManager;
use Kingdoms\models\kingdom\KingdomManager;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    /** @var Main */
    private static $object;

    /** @var EventListener */
    private $listener;

    /** @var LanguageManager */
    private $languageManager;

    /** @var PluginDatabase */
    private $pluginDatabase;

    /** @var KingdomManager */
    private $kingdomManager;

    /** @var GuildManager */
    private $guildManager;

    /** @var CommandManager */
    private $commandManager;

    public function onLoad() {
        if(!self::$object instanceof Main) {
            self::$object = $this;
        }
    }

    public function onEnable() {
        $this->initialize();
        $this->setLanguageManager();
        $this->setKingdomManager();
        $this->setGuildManager();
        $this->setPluginDatabase();
        $this->setCommandManager();
        $this->setListener();
        $this->getLogger()->info("Kingdoms was enabled.");
    }

    public function onDisable() {
        $this->getLogger()->info("Kingdoms was disabled.");
    }

    public function initialize() {
        if(!is_dir($this->getDataFolder())) @mkdir($this->getDataFolder());
        if(!is_dir($path = $this->getDataFolder() . "messages")) @mkdir($path);
        $this->saveDefaultConfig();
        $this->saveResource("messages/english.json");
        $this->saveResource("database.json");
    }

    /**
     * Return Main instance
     *
     * @return Main
     */
    public static function getInstance() {
        return self::$object;
    }

    /**
     * Return EventListener instance
     *
     * @return EventListener
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
     * Return PluginDatabase instance
     *
     * @return PluginDatabase
     */
    public function getPluginDatabase() {
        return $this->pluginDatabase;
    }

    /**
     * Returnn KingdomManager instance
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
     * Register EventListener instance
     */
    public function setListener() {
        $this->listener = new EventListener($this);
    }

    /**
     * Register LanguageManager instance
     */
    public function setLanguageManager() {
        $this->languageManager = new LanguageManager($this);
    }

    /**
     * Register PluginDatabase instance
     */
    public function setPluginDatabase() {
        $this->pluginDatabase = new PluginDatabase($this);
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

}