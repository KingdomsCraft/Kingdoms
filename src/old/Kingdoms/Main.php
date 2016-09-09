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

	/**
	 * Return Main instance
	 *
	 * @return Main
	 */
	public static function getInstance() {
		return self::$object;
	}

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

	public function initialize() {
		if(!is_dir($this->getDataFolder())) @mkdir($this->getDataFolder());
		if(!is_dir($path = $this->getDataFolder() . "messages")) @mkdir($path);
		$this->saveDefaultConfig();
		$this->saveResource("messages/english.json");
		$this->saveResource("database.json");
	}

	public function onDisable() {
		$this->getLogger()->info("Kingdoms was disabled.");
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
	 * Register EventListener instance
	 */
	public function setListener() {
		$this->listener = new EventListener($this);
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
	 * Register LanguageManager instance
	 */
	public function setLanguageManager() {
		$this->languageManager = new LanguageManager($this);
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
	 * Register PluginDatabase instance
	 */
	public function setPluginDatabase() {
		$this->pluginDatabase = new PluginDatabase($this);
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
	 * Register KingdomManager instance
	 */
	public function setKingdomManager() {
		$this->kingdomManager = new KingdomManager($this);
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
	 * Register GuildManager instance
	 */
	public function setGuildManager() {
		$this->guildManager = new GuildManager($this);
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
	 * Register CommandManager instance
	 */
	public function setCommandManager() {
		$this->commandManager = new CommandManager($this);
	}

}