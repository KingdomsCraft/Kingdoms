<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 17:08
 */

namespace Kingdoms;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    /** @var Main */
    private static $object;

    /** @var EventListener */
    private $listener;

    /** @var LanguageManager */
    private $languageManager;

    public function onLoad() {
        if(!self::$object instanceof Main) {
            self::$object = $this;
        }
    }

    public function onEnable() {
        $this->initialize();
        $this->setListener();
        $this->getLogger()->info("Kingdoms was enabled.");
    }

    public function onDisable() {
        $this->getLogger()->info("Kingdoms was disabled.");
    }

    public function initialize() {
        if(!is_dir($this->getDataFolder())) @mkdir($this->getDataFolder());
        if(!is_dir($path = $this->getDataFolder() . "messages")) @mkdir($path);
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

}