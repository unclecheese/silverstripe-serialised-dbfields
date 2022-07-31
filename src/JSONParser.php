<?php

namespace UncleCheese\SerialisedDBFields;

/**
 * Class JSONParser
 * @package UncleCheese\SerialisedDBFields
 */
class JSONParser implements SerialisedDBFieldParser
{
    /**
     * @param $data
     * @return array
     */
    public function parse($data)
    {
        return json_decode($data, true);
    }
}
