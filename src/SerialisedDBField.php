<?php

namespace UncleCheese\SerialisedDBFields;

use SilverStripe\Forms\TextareaField;
use SilverStripe\ORM\FieldType\DBText;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\FormField;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\ORM\ArrayList;

/**
 * Class SerialisedDBField
 * @package UncleCheese\SerialisedDBFields
 */
abstract class SerialisedDBField extends DBText
{

    /**
     * @var array
     */
    protected $unserialisedData;


    /**
     * @var UncleCheese\SerialisedDBFields\SerialisedDBFieldParser
     */
    protected $parser;


    /**
     * SerialisedDBField constructor.
     * @param null $name
     * @param null $unserialisedData
     */
    public function __construct($name = null, $unserialisedData = null)
    {
        $this->unserialisedData = $unserialisedData;

        parent::__construct($name);
    }

    /**
     * @param $field
     * @return bool
     */
    public function hasField(string $fieldName): bool
    {
        $data = $this->getUnserialisedData();

        return isset($data[$fieldName]);
    }


    /**
     * @param $field
     * @return mixed
     */
    public function getField(string $fieldName): mixed
    {
        return $this->obj($fieldName);
    }


    public function obj(string $fieldName, array $arguments = [], bool $cache = false): ?object
    {
        $data = $this->getFieldFromData($fieldName);

        if ($data === null) {
            return null;
        }

        if (is_array($data)) {
            if (ArrayLib::is_associative($data)) {
                $newField = static::create($this->name, $data);
                $newField->setValue($this->getValue());
            } else {
                $newField = ArrayList::create();
                foreach ($data as $item) {
                    $newField->push(
                        static::create($this->name, $item)
                    );
                }
            }
            return $newField;
        }

        $cast = $this->getCastingHint($data);

        return $cast instanceof DBField
            ? $cast
            : DBField::create_field('Text', $cast, $fieldName);
    }


    /**
     * @param null $title
     * @param null $params
     * @return FormField
     */
    public function scaffoldFormField(?string $title = null, array $params = []): ?FormField
    {
        return TextareaField::create($this->name)
            ->setTitle($title);
    }


    /**
     * @param SerialisedDBFieldParser $parser
     */
    public function setParser(SerialisedDBFieldParser $parser)
    {
        $this->parser = $parser;
    }



    /**
     * @return null
     */
    public function getUnserialisedData()
    {
        if ($this->unserialisedData) {
            return $this->unserialisedData;
        }

        return $this->unserialisedData = $this->parser->parse($this->getValue());
    }


    /**
     * @return bool
     */
    public function scalarValueOnly(): bool
    {
        return false;
    }


    /**
     * @param $field
     * @return null
     */
    protected function getFieldFromData($field)
    {
        $data = $this->getUnserialisedData();

        if (!isset($data[$field])) {
            return null;
        }

        return $data[$field];
    }


    /**
     * Parses the field value for a casting hint, e.g.
     * <code>
     * Price: Currency|20.00
     * </code>
     * @param  string $fieldValue
     * @return DBField|string
     */
    protected function getCastingHint($fieldValue)
    {
    	$pos = strpos($fieldValue, '|');
    	$dbField = null;
    	if($pos !== false) {
	    	$hint = trim(substr($fieldValue, 0, $pos));
	    	$value = substr($fieldValue, $pos+1);
	    	if(is_subclass_of($hint, DBField::class)) {
				$dbField = DBField::create_field($hint, $value);
			}

			$this->extend('updateCastingHint', $dbField, $hint, $value);
		}

		return $dbField ?: $fieldValue;
    }


}
