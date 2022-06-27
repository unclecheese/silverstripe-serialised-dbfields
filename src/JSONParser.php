<?php

namespace UncleCheese\SerialisedDBFields;

use SilverStripe\Core\Convert;

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
        return Convert::json2array($data);
    }
}
