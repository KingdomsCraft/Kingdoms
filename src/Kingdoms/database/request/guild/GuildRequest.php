<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 9:56
 */

namespace Kingdoms\database\request\guild;

use Kingdoms\database\request\Request;

abstract class GuildRequest extends Request {

    /** @var string */
    protected $guild;

    /**
     * GuildRequest constructor.
     * @param array $credentials
     * @param null|string $guild
     */
    public function __construct($credentials, $guild = null) {
        parent::__construct($credentials);
        $this->guild = strtolower($guild);
    }

    /**
     * Return class guild
     *
     * @return null|string
     */
    public function getGuild() {
        return $this->guild;
    }

    /**
     * Set class guild
     *
     * @param null|string $guild
     */
    public function setGuild($guild = null) {
        $this->guild = $guild;
    }

}