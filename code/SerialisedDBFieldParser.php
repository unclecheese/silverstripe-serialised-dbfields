<?php

namespace UncleCheese\SerialisedDBFields;

/**
 * Interface SerialisedDBFieldParser
 * Defines a tool that can be used to parse text into an array
 * @package UncleCheese\SerialisedDBFields
 */
interface SerialisedDBFieldParser
{
    /**
     * @param $data
     * @return array
     */
    public function parse($data);
}