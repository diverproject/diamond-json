<?php

namespace test\diamond\json;

use diamond\json\JsonObject;

class ExampleObjectIncludes extends JsonObject
{
	/**
	 * @JsonAnnotation({"nullable":false,"default":"Include_0x00","include":0})
	 * @var string
	 */
	private $include_0x00;
	/**
	 * @JsonAnnotation({"nullable":false,"default":"Include_0x01","include":1})
	 * @var string
	 */
	private $include_0x01;
	/**
	 * @JsonAnnotation({"nullable":false,"default":"Include_0x02","include":2})
	 * @var string
	 */
	private $include_0x02;
	/**
	 * @JsonAnnotation({"nullable":false,"default":"Include_0x04","include":4})
	 * @var string
	 */
	private $include_0x04;
}

