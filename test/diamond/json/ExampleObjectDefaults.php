<?php

namespace test\diamond\json;

use diamond\json\JsonObject;

class ExampleObjectDefaults extends JsonObject
{
	/**
	 * @JsonAnnotation({"nullable":false,"default":"yes"})
	 * @var bool
	 */
	private $boolean;
	/**
	 * @JsonAnnotation({"nullable":false,"default":"A Default String"})
	 * @var string
	 */
	private $string;
	/**
	 * @JsonAnnotation({"nullable":false,"default":"0x080"})
	 * @var int
	 */
	private $integer;
	/**
	 * @JsonAnnotation({"nullable":false,"default":1.234456789})
	 * @var float
	 */
	private $float;
	/**
	 * @JsonAnnotation({"nullable":false,"default":""})
	 * @var array
	 */
	private $array;
}

