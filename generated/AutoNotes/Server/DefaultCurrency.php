<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: proto/server.proto

namespace AutoNotes\Server;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>xelbot.com.autonotes.server.DefaultCurrency</code>
 */
class DefaultCurrency extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.xelbot.com.autonotes.server.Currency currency = 1;</code>
     */
    protected $currency = null;
    /**
     * Generated from protobuf field <code>bool found = 2;</code>
     */
    protected $found = false;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \AutoNotes\Server\Currency $currency
     *     @type bool $found
     * }
     */
    public function __construct($data = NULL) {
        \AutoNotes\Server\Meta\Server::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.xelbot.com.autonotes.server.Currency currency = 1;</code>
     * @return \AutoNotes\Server\Currency|null
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    public function hasCurrency()
    {
        return isset($this->currency);
    }

    public function clearCurrency()
    {
        unset($this->currency);
    }

    /**
     * Generated from protobuf field <code>.xelbot.com.autonotes.server.Currency currency = 1;</code>
     * @param \AutoNotes\Server\Currency $var
     * @return $this
     */
    public function setCurrency($var)
    {
        GPBUtil::checkMessage($var, \AutoNotes\Server\Currency::class);
        $this->currency = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool found = 2;</code>
     * @return bool
     */
    public function getFound()
    {
        return $this->found;
    }

    /**
     * Generated from protobuf field <code>bool found = 2;</code>
     * @param bool $var
     * @return $this
     */
    public function setFound($var)
    {
        GPBUtil::checkBool($var);
        $this->found = $var;

        return $this;
    }

}

