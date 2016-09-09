<?php

/**
 * Kingdoms Craft Kingdoms
 *
 * Copyright (C) 2016 Kingdoms Craft
 *
 * This is private software, you cannot redistribute it and/or modify any way
 * unless otherwise given permission to do so. If you have not been given explicit
 * permission to view or modify this software you should take the appropriate actions
 * to remove this software from your device immediately.
 *
 * @author JackNoordhuis
 */

namespace kingdoms;

use kingdomscraft\economy\Economy;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use kingdomscraft\Main as EconomyPlugin;
use pocketmine\utils\PluginException;

class Main extends PluginBase {

	/** @var Config */
	private $settings;

	/** @var Economy */
	protected $economy;

	/* Resources */
	const SETTINGS_FILE = "Settings.yml";

	public function onEnable() {
		$this->loadConfigs();
		$economy = $this->getServer()->getPluginManager()->getPlugin("Economy");
		if(!$economy instanceof EconomyPlugin) throw new PluginException("Economy plugin isn't loaded!");
		$this->economy = $economy->getEconomy();
	}

	public function loadConfigs() {
		$this->saveResource(self::SETTINGS_FILE);
		$this->settings = new Config($this->getDataFolder() . self::SETTINGS_FILE);
	}

	/**
	 * @return Config
	 */
	public function getSettings() {
		 return $this->settings;
	}

}