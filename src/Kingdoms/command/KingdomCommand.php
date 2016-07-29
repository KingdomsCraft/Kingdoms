<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:39
 */

namespace Kingdoms\command;

use Kingdoms\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class KingdomCommand extends Command {

    /** @var Main */
    private $plugin;

    /**
     * KingdomCommand constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        parent::__construct("kingdom", "Kingdom command", "Usage: /k help", ["k", "kin"]);
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        // TODO: Implement execute() method.
    }

}