<?php

namespace test\diamond\json;

use diamond\json\JsonObject;

class ExampleObjectNulls extends JsonObject
{
	/**
	 * @JsonAnnotation({"nullable":false})
	 * @var bool
	 */
	private $boolean;
	/**
	 * @JsonAnnotation({"nullable":false})
	 * @var string
	 */
	private $string;
	/**
	 * @JsonAnnotation({"nullable":false})
	 * @var int
	 */
	private $integer;
	/**
	 * @JsonAnnotation({"nullable":false})
	 * @var float
	 */
	private $float;
	/**
	 * @JsonAnnotation({"nullable":false})
	 * @var array
	 */
	private $array;
	/**
	 * @JsonAnnotation({"nullable":true})
	 * @var bool
	 */
	private $booleanNull;
	/**
	 * @JsonAnnotation({"nullable":true})
	 * @var string
	 */
	private $stringNull;
	/**
	 * @JsonAnnotation({"nullable":true})
	 * @var int
	 */
	private $integerNull;
	/**
	 * @JsonAnnotation({"nullable":true})
	 * @var float
	 */
	private $floatNull;
	/**
	 * @JsonAnnotation({"nullable":true})
	 * @var array
	 */
	private $arrayNull;
}

