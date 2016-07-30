<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 17:12
 */

namespace Kingdoms\language;

use Kingdoms\Main;
use Kingdoms\Utils;

class LanguageManager {

    /** @var LanguageManager */
    private static $object;

    /** @var Main */
    private $plugin;

    /** @var array */
    private $messages;

    /**
     * LanguageManager constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        self::$object = $this;
        $this->plugin = $plugin;
        $this->init();
    }

    /**
     * Initialize LanguageManager, parse messages
     */
    private function init() {
        $config = $this->plugin->getConfig()->getAll();
        if(is_file($file = $this->plugin->getDataFolder() . "messages" . DIRECTORY_SEPARATOR . $config["msgFile"])) {
            $this->messages = json_decode(file_get_contents($file), true);
        }
        else {
            $this->plugin->getLogger()->critical("Couldn't initialize LanguageManager due {$config["msgFile"]} is not a valid file!");
        }
    }

    /**
     * Return LanguageManager instance
     *
     * @return LanguageManager
     */
    public static function getInstance() {
        return self::$object;
    }

    /**
     * Return a message by key
     *
     * @param string $key
     * @return string
     */
    public function getMessage($key) {
        if(isset($this->messages[$key])) {
            return Utils::translateColours($this->messages[$key]);
        }
        else {
            $this->plugin->getLogger()->critical("Couldn't get message {$key} due it's not set!");
            return "";
        }
    }

}