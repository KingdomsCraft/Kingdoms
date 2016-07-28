<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 19:15
 */

namespace Kingdoms\database\request\kingdom;

use Kingdoms\database\request\Request;

abstract class KingdomRequest extends Request {

    /** @var string */
    protected $kingdom;

    /**
     * KingdomRequest constructor.
     * @param array $credentials
     * @param string|null $kingdom
     */
    public function __construct($credentials, $kingdom = null) {
        parent::__construct($credentials);
        $this->kingdom = strtolower($kingdom);
    }

    /**
     * @return string
     */
    public function getKingdom() {
        return $this->kingdom;
    }

    /**
     * @param string $kingdom
     */
    public function setKingdom($kingdom) {
        $this->kingdom = $kingdom;
    }



}