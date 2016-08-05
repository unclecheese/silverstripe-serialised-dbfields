<?php

namespace UncleCheese\SerialisedDBFields;

use \Convert;

class JSONParser implements SerialisedDBFieldParser
{
	public function parse($data)
	{
		return Convert::json2array($data);
	}
}