<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 23:30
 */

namespace Kingdoms\command;

use Kingdoms\command\guild\GuildSubCommand;
use Kingdoms\command\kingdom\AddPointsByPlayerNameCommand;
use Kingdoms\command\kingdom\AddPointsCommand;
use Kingdoms\command\kingdom\BroadcastCommand;
use Kingdoms\command\kingdom\CreateCommand;
use Kingdoms\command\kingdom\DeleteCommand;
use Kingdoms\command\kingdom\HealCommand;
use Kingdoms\command\kingdom\HelpCommand;
use Kingdoms\command\kingdom\HomeCommand;
use Kingdoms\command\kingdom\InfoCommand;
use Kingdoms\command\kingdom\JoinCommand;
use Kingdoms\command\kingdom\KingdomSubCommand;
use Kingdoms\command\kingdom\LeaveCommand;
use Kingdoms\command\kingdom\ListCommand;
use Kingdoms\command\kingdom\MottoCommand;
use Kingdoms\command\kingdom\RemovePointsCommand;
use Kingdoms\command\kingdom\SetCitizenCommand;
use Kingdoms\command\kingdom\SetHomeCommand;
use Kingdoms\command\kingdom\SetLeaderCommand;
use Kingdoms\command\kingdom\SetNoblemanCommand;
use Kingdoms\command\kingdom\SetPointsCommand;
use Kingdoms\command\kingdom\TopCommand;
use Kingdoms\command\guild\HelpCommand as GuildHelpCommand;
use Kingdoms\command\guild\CreateCommand as GuildCreateCommand;
use Kingdoms\Main;
use pocketmine\command\CommandSender;

class CommandManager {

    /** @var Main */
    private $plugin;

    /** @var KingdomCommand */
    private $kingdomCommand;

    /** @var KingdomSubCommand[] */
    private $kingdomCommands = [];

    /** @var GuildCommand */
    private $guildCommand;

    /** @var GuildSubCommand[] */
    private $guildCommands = [];

    /**
     * CommandManager constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $this->init();
    }

    /**
     * Initialize the class
     */
    public function init() {
        $this->registerKingdomCommands();
        $this->registerGuildCommands();
        $this->registerAll();
    }

    /**
     * Register all kingdom commands
     */
    public function registerKingdomCommands() {
        $this->kingdomCommand = new KingdomCommand($this);
        $this->kingdomCommands["help"] = new HelpCommand($this);
        $this->kingdomCommands["create"] = new CreateCommand($this);
        $this->kingdomCommands["top"] = new TopCommand($this);
        $this->kingdomCommands["info"] = new InfoCommand($this);
        $this->kingdomCommands["home"] = new HomeCommand($this);
        $this->kingdomCommands["sethome"] = new SetHomeCommand($this);
        $this->kingdomCommands["list"] = new ListCommand($this);
        $this->kingdomCommands["join"] = new JoinCommand($this);
        $this->kingdomCommands["leave"] = new LeaveCommand($this);
        $this->kingdomCommands["heal"] = new HealCommand($this);
        $this->kingdomCommands["broadcast"] = new BroadcastCommand($this);
        $this->kingdomCommands["delete"] = new DeleteCommand($this);
        $this->kingdomCommands["motto"] = new MottoCommand($this);
        $this->kingdomCommands["setcitizen"] = new SetCitizenCommand($this);
        $this->kingdomCommands["setnobleman"] = new SetNoblemanCommand($this);
        $this->kingdomCommands["setleader"] = new SetLeaderCommand($this);
        $this->kingdomCommands["setpoints"] = new SetPointsCommand($this);
        $this->kingdomCommands["addpoints"] = new AddPointsCommand($this);
        $this->kingdomCommands["removepoints"] = new RemovePointsCommand($this);
        $this->kingdomCommands["addpointsbyplayername"] = new AddPointsByPlayerNameCommand($this);
    }

    /**
     * Register all guild commands
     */
    public function registerGuildCommands() {
        $this->guildCommand = new GuildCommand($this);
        $this->guildCommands["help"] = new GuildHelpCommand($this);
        $this->guildCommands["create"] = new GuildCreateCommand($this);
    }

    /**
     * Register all commands
     */
    public function registerAll() {
        $commandMap = $this->plugin->getServer()->getCommandMap();
        $commandMap->register("kingdom", $this->kingdomCommand);
        $commandMap->register("guild", $this->guildCommand);
    }

    /**
     * Execute a Kingdom subcommand
     *
     * @param string $command
     * @param CommandSender $sender
     * @param array $args
     */
    public function kingdom_execute($command, CommandSender $sender, $args) {
        unset($args[0]);
        $args = implode(" ", $args);
        $args = explode(" ", $args);
        $this->kingdomCommands[$command]->execute($sender, $args);
    }

    /**
     * Execute a command
     *
     * @param string $command
     * @param CommandSender $sender
     * @param array $args
     */
    public function guild_execute($command, CommandSender $sender, $args) {
        unset($args[0]);
        $args = implode(" ", $args);
        $args = explode(" ", $args);
        $this->guildCommands[$command]->execute($sender, $args);
    }

    /**
     * Return Main instance
     *
     * @return Main
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * Return KingdomSubCommand array
     *
     * @return KingdomSubCommand[]
     */
    public function getKingdomCommands() {
        return $this->kingdomCommands;
    }

}