<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 14:44
 */

namespace Kingdoms\models;

use Kingdoms\Base;

class LanguageManager {

    /** @var LanguageManager */
    private static $object;

    /** @var Base */
    private $plugin;

    /** @var string */
    private $language;

    /**
     * LanguageManager constructor.
     * @param Base $plugin
     * @param string $language
     */
    public function __construct(Base $plugin, $language) {
        $this->plugin = $plugin;
        $this->language = $language;
        self::$object = $this;
    }

    /**
     * @return LanguageManager
     */
    public static function getInstance() {
        return self::$object;
    }

    /**
     * Return language name.
     *
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Return true if there's a valid message container for messages.
     *
     * @return bool
     */
    public function isLanguage() {
        if(is_file($this->plugin->getDataFolder() . "messages" . DIRECTORY_SEPARATOR . "{$this->language}.json")) {
            return true;
        }
        else {
            $this->plugin->getLogger()->critical("Couldn't get messages without a valid language set!");
            return false;
        }
    }

    /**
     * Return all messages in an array, null if there isn't a valid language set.
     *
     * @return array|null
     */
    public function getMessages() {
        if($this->isLanguage()) {
            return json_decode(file_get_contents($this->plugin->getDataFolder() . "messages" . DIRECTORY_SEPARATOR . "{$this->language}.json"), true);
        }
        else {
            return null;
        }
    }

    /**
     * Return message, null if it's not registered or there isn't a valid language set.
     *
     * @param string $key
     * @return string
     */
    public function getMessage($key) {
        if($this->isLanguage()) {
            $messages = $this->getMessages();
            if(isset($messages[$key])) {
                return Utils::getColoured($messages[$key]);
            }
            else {
                $this->plugin->getLogger()->critical("Couldn't get message {$key} due it doesn't exists!");
                return "";
            }
        }
        else {
            return "";
        }
    }

    /**
     * Set language name
     *
     * @param string $language
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

}