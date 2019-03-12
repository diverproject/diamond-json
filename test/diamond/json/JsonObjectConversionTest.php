<?php

namespace test\diamond\json;

use diamond\json\JsonUtil;

class JsonObjectConversionTest extends DiamondJsonTest
{
	private $exampleObjectTypes;
	private $exampleObjectFormats;

	protected function setUp()
	{
		parent::setUp();

		$this->exampleObjectTypes = new ExampleObjectTypes();
		$this->exampleObjectFormats = new ExampleObjectFormats();
	}

	protected function tearDown()
	{
		$this->exampleObjectFormats = null;
		$this->exampleObjectTypes = null;

		parent::tearDown();
	}

	public function testTypes()
	{
		$entry = [
			'boolean' => ($boolean = true),
			'integer' => ($integer = rand()),
			'float' => ($float = frand(0.0, 10.0, 4)),
			'string' => ($string = 'a string from array data'),
			'array' => ($array = [rand(), rand(), rand()]),
			'datetime' => date(JsonUtil::DATETIME_FORMAT, ($timestamp = time())),
			'self' => [
				'boolean' => !$boolean,
				'integer' => $integer,
				'float' => $float,
				'string' => $string,
				'array' => $array,
				'datetime' => $timestamp,
			],
			'unknow' => [
				JsonUtil::CLASS_NAME_FIELD => ExampleObjectTypes::class,
				'boolean' => !$boolean,
				'integer' => $integer,
				'float' => $float,
				'string' => $string,
				'array' => $array,
				'datetime' => $timestamp,
			],
			'method' => [
				'boolean' => !$boolean,
				'integer' => $integer,
				'float' => $float,
				'string' => $string,
				'array' => $array,
				'datetime' => $timestamp,
			],
		];
		$this->exampleObjectTypes->fromArray($entry);

		$this->assertEquals($this->exampleObjectTypes->isBoolean(), $boolean);
		$this->assertEquals($this->exampleObjectTypes->getInteger(), $integer);
		$this->assertEquals($this->exampleObjectTypes->getFloat(), $float);
		$this->assertEquals($this->exampleObjectTypes->getString(), $string);
		$this->assertEquals($this->exampleObjectTypes->getArray(), $array);
		$this->assertEquals($this->exampleObjectTypes->getDatetime()->getTimestamp(), $timestamp);
		$this->assertEquals($this->exampleObjectTypes->getSelf()->isBoolean(), !$boolean);
		$this->assertEquals($this->exampleObjectTypes->getSelf()->getInteger(), $integer);
		$this->assertEquals($this->exampleObjectTypes->getSelf()->getFloat(), $float);
		$this->assertEquals($this->exampleObjectTypes->getSelf()->getString(), $string);
		$this->assertEquals($this->exampleObjectTypes->getSelf()->getArray(), $array);
		$this->assertEquals($this->exampleObjectTypes->getUnknow()->getDatetime()->getTimestamp(), $timestamp);
		$this->assertEquals($this->exampleObjectTypes->getUnknow()->isBoolean(), !$boolean);
		$this->assertEquals($this->exampleObjectTypes->getUnknow()->getInteger(), $integer);
		$this->assertEquals($this->exampleObjectTypes->getUnknow()->getFloat(), $float);
		$this->assertEquals($this->exampleObjectTypes->getUnknow()->getString(), $string);
		$this->assertEquals($this->exampleObjectTypes->getUnknow()->getArray(), $array);
		$this->assertEquals($this->exampleObjectTypes->getMethod()->getDatetime()->getTimestamp(), $timestamp);
		$this->assertEquals($this->exampleObjectTypes->getMethod()->isBoolean(), !$boolean);
		$this->assertEquals($this->exampleObjectTypes->getMethod()->getInteger(), $integer);
		$this->assertEquals($this->exampleObjectTypes->getMethod()->getFloat(), $float);
		$this->assertEquals($this->exampleObjectTypes->getMethod()->getString(), $string);
		$this->assertEquals($this->exampleObjectTypes->getMethod()->getArray(), $array);
		$this->assertEquals($this->exampleObjectTypes->getMethod()->getDatetime()->getTimestamp(), $timestamp);
	}

	public function testFormats()
	{
		$this->exampleObjectFormats->setBoolean(true);
		$this->exampleObjectFormats->setInteger(12);
		$this->exampleObjectFormats->setFloat(1.234);
		$this->exampleObjectFormats->setString('test');

		$array = $this->exampleObjectFormats->toArray();
		$this->assertEquals('ok', $array['boolean']);
		$this->assertEquals('012', $array['integer']);
		$this->assertEquals('1.23', $array['float']);
		$this->assertEquals('a string format for test', $array['string']);

		$this->exampleObjectFormats->setBoolean(false);
		$this->exampleObjectFormats->setInteger(1234);
		$this->exampleObjectFormats->setFloat(1.1);
		$this->exampleObjectFormats->setString('another test');

		$array = $this->exampleObjectFormats->toArray();
		$this->assertEquals('nok', $array['boolean']);
		$this->assertEquals('1234', $array['integer']);
		$this->assertEquals('1.10', $array['float']);
		$this->assertEquals('a string format for another test', $array['string']);
	}

	public function testMethods()
	{
		$exampleObjectMethods = new ExampleObjectMethods();

		$array = $exampleObjectMethods->toArray();
		$this->assertEquals(false, $array['boolean']);
		$this->assertEquals(0, $array['integer']);
		$this->assertEquals(0.0, $array['float']);
		$this->assertEquals('', $array['string']);
		$this->assertEquals(null, $array['booleanNull']);
		$this->assertEquals(null, $array['integerNull']);
		$this->assertEquals(null, $array['floatNull']);
		$this->assertEquals(null, $array['stringNull']);

		$entry = [
			'boolean' => ($boolean = true),
			'integer' => ($integer = rand()),
			'float' => ($float = frand(0.0, 10.0, 4)),
			'string' => ($string = 'a string from array data'),
			'booleanNull' => ($booleanNull = true),
			'integerNull' => ($integerNull = rand()),
			'floatNull' => ($floatNull = frand(0.0, 10.0, 4)),
			'stringNull' => ($stringNull = 'a string from array data'),
		];

		$exampleObjectMethods->fromArray($entry);
		$array = $exampleObjectMethods->toArray();
		$this->assertEquals($boolean, $array['boolean']);
		$this->assertEquals($integer, $array['integer']);
		$this->assertEquals($float, $array['float']);
		$this->assertEquals($string, $array['string']);
		$this->assertEquals($booleanNull, $array['booleanNull']);
		$this->assertEquals($integerNull, $array['integerNull']);
		$this->assertEquals($floatNull, $array['floatNull']);
		$this->assertEquals($stringNull, $array['stringNull']);
		$this->assertEquals($boolean, $exampleObjectMethods->isBoolean());
		$this->assertEquals($integer, $exampleObjectMethods->getInteger());
		$this->assertEquals($float, $exampleObjectMethods->getFloat());
		$this->assertEquals($string, $exampleObjectMethods->getString());
		$this->assertEquals($booleanNull, $exampleObjectMethods->isBooleanNull());
		$this->assertEquals($integerNull, $exampleObjectMethods->getIntegerNull());
		$this->assertEquals($floatNull, $exampleObjectMethods->getFloatNull());
		$this->assertEquals($stringNull, $exampleObjectMethods->getStringNull());
	}
}

