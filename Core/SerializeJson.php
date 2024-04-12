<?php

namespace SlimSecure\Core;

/**
 * Slimez SerializeJson Class
 *
 * Author: Oaad Global
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
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
