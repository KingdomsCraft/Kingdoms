<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 7:48
 */

namespace Kingdoms\database;

use Kingdoms\Base;

abstract class DatabaseManager implements Database {

    /** @var Base */
    protected $plugin;

    /**
     * DatabaseManager constructor.
     *
     * @param Base $plugin
     */
    public function __construct(Base $plugin){
        $this->plugin = $plugin;
        $this->init();
    }

}