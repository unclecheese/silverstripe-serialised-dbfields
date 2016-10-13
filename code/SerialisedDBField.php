<?php

namespace UncleCheese\SerialisedDBFields;

use \Text;
use \DBField;
use \FormField;
use \ArrayLib;
use \ArrayList;

/**
 * Class SerialisedDBField
 * @package UncleCheese\SerialisedDBFields
 */
abstract class SerialisedDBField extends Text
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
     * @var FormField
     */
    protected $editorField;


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
    public function hasField($field)
    {
        $data = $this->getUnserialisedData();

        return isset($data[$field]);
    }


    /**
     * @param $field
     * @return mixed
     */
    public function getField($field)
    {
        return $this->obj($field);
    }


    /**
     * @param $fieldName
     * @param null $arguments
     * @param bool $forceReturnedObject
     * @param bool $cache
     * @param null $cacheName
     * @return null
     */
    public function obj($fieldName, $arguments = null, $forceReturnedObject = true, $cache = false, $cacheName = null)
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

        $this->$fieldName = $this->getCastingHint($data);

        return parent::obj($fieldName, $arguments, $forceReturnedObject, $cache, $cacheName);
    }


    /**
     * @param null $title
     * @param null $params
     * @return FormField
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return $this->editorField
            ->setName($this->name)
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
     * @param FormField $field
     */
    public function setEditor(FormField $field)
    {
        $this->editorField = $field;
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
	    	if(is_subclass_of($hint, 'DBField')) {	    			    		
				$dbField = DBField::create_field($hint, $value);
			}

			$this->extend('updateCastingHint', $dbField, $hint, $value);
		}

		return $dbField ?: $fieldValue;
    }


}