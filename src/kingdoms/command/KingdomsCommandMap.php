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

namespace kingdoms\command;

use kingdoms\Main;

class KingdomsCommandMap {

	/** @var Main */
	private $plugin;

	/** @var KingdomsCommand[] */
	protected $commands = [];

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		$this->setDefaultCommands();
	}

	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * Set the default commands
	 */
	public function setDefaultCommands() {
		$this->registerAll([
		]);
	}

	/**
	 * Register an array of commands
	 *
	 * @param array $commands
	 */
	public function registerAll(array $commands) {
		foreach($commands as $command) {
			$this->register($command);
		}
	}

	/**
	 * Register a command
	 *
	 * @param KingdomsCommand $command
	 * @param string $fallbackPrefix
	 *
	 * @return bool
	 */
	public function register(KingdomsCommand $command, $fallbackPrefix = "kc") {
		if($command instanceof KingdomsCommand) {
			$this->plugin->getServer()->getCommandMap()->register($fallbackPrefix, $command);
			$this->commands[strtolower($command->getName())] = $command;
		}
		return false;
	}

	/**
	 * Unregisters all commands
	 */
	public function clearCommands() {
		foreach($this->commands as $command) {
			$this->unregister($command);
		}
		$this->commands = [];
		$this->setDefaultCommands();
	}

	/**
	 * Unregister a command
	 *
	 * @param KingdomsCommand $command
	 */
	public function unregister(KingdomsCommand $command) {
		$command->unregister($this->plugin->getServer()->getCommandMap());
		unset($this->commands[strtolower($command->getName())]);
	}

	/**
	 * Get a command
	 *
	 * @param $name
	 *
	 * @return KingdomsCommand|null
	 */
	public function getCommand($name) {
		if(isset($this->commands[$name])) {
			return $this->commands[$name];
		}
		return null;
	}

	/**
	 * @return KingdomsCommand[]
	 */
	public function getCommands() {
		return $this->commands;
	}

	public function __destruct() {
		$this->close();
	}

	public function close() {
		foreach($this->commands as $command) {
			$this->unregister($command);
		}
		unset($this->commands, $this->plugin);
	}

}