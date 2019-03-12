<?php

namespace test\diamond\json;

use diamond\json\JsonObject;

class ExampleObjectFormats extends JsonObject
{
	/**
	 * @JsonAnnotation({"nullable":false,"format":"ok;nok"})
	 * @var bool
	 */
	private $boolean;
	/**
	 * @JsonAnnotation({"nullable":false,"format":"%03d"})
	 * @var int
	 */
	private $integer;
	/**
	 * @JsonAnnotation({"nullable":false,"format":"%3.2f"})
	 * @var float
	 */
	private $float;
	/**
	 * @JsonAnnotation({"nullable":false,"format":"a string format for %s","default":"StringValue"})
	 * @var string
	 */
	private $string;
	/**
	 * @JsonAnnotation({"nullable":true,"format":"yes;no"})
	 * @var bool
	 */
	private $booleanNull;
	/**
	 * @JsonAnnotation({"nullable":true,"format":"%3d"})
	 * @var int
	 */
	private $integerNull;
	/**
	 * @JsonAnnotation({"nullable":true,"format":"%03.3f"})
	 * @var float
	 */
	private $floatNull;
	/**
	 * @JsonAnnotation({"nullable":true,"format":"a non nullable string format for %s"})
	 * @var string
	 */
	private $stringNull;

	/**
	 * @return bool
	 */
	public function isBoolean(): bool
	{
		return $this->boolean;
	}

	/**
	 * @param bool $boolean
	 */
	public function setBoolean(bool $boolean): void
	{
		$this->boolean = $boolean;
	}

	/**
	 * @return int
	 */
	public function getInteger(): int
	{
		return $this->integer;
	}

	/**
	 * @param int $integer
	 */
	public function setInteger(int $integer)
	{
		$this->integer = $integer;
	}

	/**
	 * @return float
	 */
	public function getFloat(): float
	{
		return $this->float;
	}

	/**
	 * @param float $float
	 */
	public function setFloat(float $float): void
	{
		$this->float = $float;
	}

	/**
	 * @return string
	 */
	public function getString(): string
	{
		return $this->string;
	}

	/**
	 * @param string $string
	 */
	public function setString(string $string): void
	{
		$this->string = $string;
	}

	/**
	 * @return bool|NULL
	 */
	public function isBooleanNull(): ?bool
	{
		return $this->booleanNull;
	}

	/**
	 * @param bool|NULL $booleanNull
	 */
	public function setBooleanNull(?bool $booleanNull): void
	{
		$this->booleanNull = $booleanNull;
	}

	/**
	 * @return int
	 */
	public function getIntegerNull(): ?int
	{
		return $this->integerNull;
	}

	/**
	 * @param int|NULL $integerNull
	 */
	public function setIntegerNull(?int $integerNull): void
	{
		$this->integerNull = $integerNull;
	}

	/**
	 * @return float
	 */
	public function getFloatNull(): ?float
	{
		return $this->floatNull;
	}

	/**
	 * @param float|NULL $floatNull
	 */
	public function setFloatNull(?float $floatNull): void
	{
		$this->floatNull = $floatNull;
	}

	/**
	 * @return string|NULL
	 */
	public function getStringNull(): ?string
	{
		return $this->stringNull;
	}

	/**
	 * @param string|NULL $stringNull
	 */
	public function setStringNull(?string $stringNull): void
	{
		$this->stringNull = $stringNull;
	}
}

