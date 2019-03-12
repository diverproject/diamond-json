<?php

namespace test\diamond\json;

use diamond\json\JsonObject;

class ExampleObjectExcludes extends JsonObject
{
	/**
	 * @JsonAnnotation({"nullable":false,"default":"Exclude_0x00","exclude":0})
	 * @var string
	 */
	private $exclude_0x00;
	/**
	 * @JsonAnnotation({"nullable":false,"default":"Exclude_0x01","exclude":1})
	 * @var string
	 */
	private $exclude_0x01;
	/**
	 * @JsonAnnotation({"nullable":false,"default":"Exclude_0x02","exclude":2})
	 * @var string
	 */
	private $exclude_0x02;
	/**
	 * @JsonAnnotation({"nullable":false,"default":"Exclude_0x04","exclude":4})
	 * @var string
	 */
	private $exclude_0x04;
}

