<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:41
 */

namespace Kingdoms\command;

use Kingdoms\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class GuildCommand extends Command {

    /** @var Main */
    private $plugin;

    /**
     * GuildCommand constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        parent::__construct("guild", "Guild command", "/g help", ["g"]);
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        // TODO: Implement execute() method.
    }

}