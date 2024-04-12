<?php

namespace SlimSecure\Core;

/**
 * SlimSecure SerializeJson Class
 *
 * Author: Engineer Usiobaifo Kenneth
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: SlimSecure
 * Description: SlimSecure.
 */
class SerializeJson implements \JsonSerializable
{
    protected $array;

    /**
     * SerializeJson constructor.
     *
     * @param array $array The array to be serialized.
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * Serialize the object to JSON.
     *
     * @return mixed The serialized JSON representation of the object.
     */
    public function jsonSerialize(): mixed
    {
        return $this->array;
    }
}
