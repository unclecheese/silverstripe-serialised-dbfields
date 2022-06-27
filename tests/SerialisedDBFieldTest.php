<?php

namespace UncleCheese\SerialisedDBFields\Tests;

class SerialisedDBFieldTest extends SapphireTest
{

    public function testItAcceptsUnserialisedData()
    {
        $field = TestSerialisedDBField::create('test', ['foo' => 'bar']);
        $field->setParser(new SerialisedDBFieldTest_Parser());

        $this->assertArrayHasKey('foo', $field->getUnserialisedData());
    }

    public function testHasField()
    {
        $field = TestSerialisedDBField::create('test');
        $field->setParser(new SerialisedDBFieldTest_Parser());

        $this->assertTrue($field->hasField('Foo'));
        $this->assertFalse($field->hasField('Bar'));
    }

    public function testObj()
    {
        $field = TestSerialisedDBField::create('test');
        $field->setParser(new SerialisedDBFieldTest_Parser());

        $this->assertInstanceOf('Text', $field->obj('Foo'));
        $this->assertEquals('Bar', $field->obj('Foo')->getValue());
        $this->assertNull($field->obj('Bar'));
        $this->assertInstanceOf('ArrayList', $field->obj('List'));
        $this->assertEquals(2, $field->obj('List')->count());
        $this->assertInstanceOf(
            'Unclecheese\SerialisedDBFields\SerialisedDBField',
            $field->obj('List')->first()
        );
        $this->assertInstanceOf('Text', $field->obj('List')->first()->obj('Title'));
        $this->assertEquals('One', $field->obj('List')->first()->obj('Title')->getValue());
    }

    public function testCastingHints()
    {
        $field = TestSerialisedDBField::create('test');
        $field->setParser(new SerialisedDBFieldTest_Parser());

        $this->assertInstanceOf('Currency', $field->obj('Price'));
        $this->assertEquals(20, $field->obj('Price')->getValue());

        $this->assertInstanceOf('Date', $field->obj('StartDate'));
        $this->assertEquals('2015-01-01', $field->obj('StartDate')->getValue());
    }

}


class SerialisedDBFieldTest_Parser implements UncleCheese\SerialisedDBFields\SerialisedDBFieldParser
{
    public function parse($data)
    {
        return [
            'Foo' => 'Bar',
            'Qux' => 'Baz',
            'List' => [
                ['Title' => 'One', 'Link' => '/one'],
                ['Title' => 'Two', 'Link' => '/two']
            ],
            'Price' => 'Currency|20',
            'StartDate' => 'Date | 2015-01-01'
        ];
    }
}

class TestSerialisedDBField extends UncleCheese\SerialisedDBFields\SerialisedDBField
{

}
