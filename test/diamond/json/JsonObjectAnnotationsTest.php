<?php

namespace test\diamond\json;

use diamond\lang\Bitwise;

class JsonObjectAnnotationsTest extends DiamondJsonTest
{
	private $exampleObjectNulls;
	private $exampleObjectDefaults;
	private $exampleObjectFormats;
	private $exampleObjectIncludes;
	private $exampleObjectExcludes;

	protected function setUp()
	{
		parent::setUp();

		$this->exampleObjectNulls = new ExampleObjectNulls();
		$this->exampleObjectDefaults = new ExampleObjectDefaults();
		$this->exampleObjectFormats = new ExampleObjectFormats();
		$this->exampleObjectIncludes = new ExampleObjectIncludes();
		$this->exampleObjectExcludes = new ExampleObjectExcludes();
	}

	protected function tearDown()
	{
		$this->exampleObjectExcludes = null;
		$this->exampleObjectIncludes = null;
		$this->exampleObjectFormats = null;
		$this->exampleObjectDefaults = null;
		$this->exampleObjectNulls = null;

		parent::tearDown();
	}

	public function testExampleObjectNulls()
	{
		$array = $this->exampleObjectNulls->toArray();

		$this->assertEquals(false, $array['boolean']);
		$this->assertEquals('', $array['string']);
		$this->assertEquals(0, $array['integer']);
		$this->assertEquals(0.0, $array['float']);
		$this->assertEquals([], $array['array']);
		$this->assertNull($array['booleanNull']);
		$this->assertNull($array['integerNull']);
		$this->assertNull($array['floatNull']);
		$this->assertNull($array['stringNull']);
		$this->assertNull($array['arrayNull']);
	}

	public function testExampleObjectDefaults()
	{
		$array = $this->exampleObjectDefaults->toArray();

		$this->assertEquals(true, $array['boolean']);
		$this->assertEquals('A Default String', $array['string']);
		$this->assertEquals(0, $array['integer']);
		$this->assertEquals(0.0, $array['float']);
		$this->assertEquals([], $array['array']);
	}

	public function testExampleObjectFormats()
	{
		$array = $this->exampleObjectFormats->toArray();

		$this->assertEquals('nok', $array['boolean']);
		$this->assertEquals('000', $array['integer']);
		$this->assertEquals('0.00', $array['float']);
		$this->assertEquals('a string format for StringValue', $array['string']);
		$this->assertNull($array['booleanNull']);
		$this->assertNull($array['integerNull']);
		$this->assertNull($array['floatNull']);
		$this->assertNull($array['stringNull']);
	}

	public function testExampleObjectIncludes()
	{
		$array = $this->exampleObjectIncludes->toArray(0);
		$this->assertTrue(isset($array['include_0x00']));
		$this->assertTrue(isset($array['include_0x01']));
		$this->assertTrue(isset($array['include_0x02']));
		$this->assertTrue(isset($array['include_0x04']));

		for ($i = 1; $i <= 32; $i++)
		{
			$array = $this->exampleObjectIncludes->toArray($i);
			$this->assertEquals(false, isset($array['include_0x00'])); // Although it's true we have a "exception" for zero include
			$this->assertEquals(Bitwise::hasPropertie($i, 1), isset($array['include_0x01']));
			$this->assertEquals(Bitwise::hasPropertie($i, 2), isset($array['include_0x02']));
			$this->assertEquals(Bitwise::hasPropertie($i, 4), isset($array['include_0x04']));
		}
	}

	public function testExampleObjectExcludes()
	{
		$array = $this->exampleObjectExcludes->toArray(0);
		$this->assertTrue(isset($array['exclude_0x00']));
		$this->assertTrue(isset($array['exclude_0x01']));
		$this->assertTrue(isset($array['exclude_0x02']));
		$this->assertTrue(isset($array['exclude_0x04']));

		for ($i = 1; $i <= 32; $i++)
		{
			$array = $this->exampleObjectExcludes->toArray($i * -1);
			$this->assertEquals(!Bitwise::hasPropertie($i, 0), isset($array['exclude_0x00']));
			$this->assertEquals(!Bitwise::hasPropertie($i, 1), isset($array['exclude_0x01']));
			$this->assertEquals(!Bitwise::hasPropertie($i, 2), isset($array['exclude_0x02']));
			$this->assertEquals(!Bitwise::hasPropertie($i, 4), isset($array['exclude_0x04']));
		}
	}
}

