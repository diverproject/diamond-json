<?php

namespace test\diamond\json;

use PHPUnit\Framework\TestSuite;

/**
 * Static test suite.
 */
class JsonSuite extends TestSuite
{
	/**
	 * Constructs the test suite handler.
	 */
	public function __construct()
	{
		$this->setName('CollectionSuite');
		$this->addTest(JsonObjectAnnotationsTest::class);
		$this->addTest(JsonObjectConversionTest::class);
	}

	/**
	 * Creates the suite.
	 */
	public static function suite()
	{
		return new self();
	}
}

