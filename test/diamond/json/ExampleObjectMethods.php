<?php

namespace test\diamond\json;

use diamond\json\JsonObject;

class ExampleObjectMethods extends JsonObject
{
	/**
	 * @JsonAnnotation({"name":"boolean","nullable":false})
	 * @return bool
	 */
	public function isBoolean(): bool
	{
		return isset($this->boolean) ? $this->boolean : false;
	}

	/**
	 * @JsonAnnotation({"name":"boolean","nullable":false})
	 * @param bool $boolean
	 */
	public function setBoolean(bool $boolean): void
	{
		$this->boolean = $boolean;
	}

	/**
	 * @JsonAnnotation({"name":"integer","nullable":false})
	 * @return int
	 */
	public function getInteger(): int
	{
		return isset($this->integer) ? $this->integer : 0;
	}

	/**
	 * @JsonAnnotation({"name":"integer","nullable":false})
	 * @param int $integer
	 */
	public function setInteger(int $integer)
	{
		$this->integer = $integer;
	}

	/**
	 * @JsonAnnotation({"name":"float","nullable":false})
	 * @return float
	 */
	public function getFloat(): float
	{
		return isset($this->float) ? $this->float : 0.0;
	}

	/**
	 * @JsonAnnotation({"name":"float","nullable":false})
	 * @param float $float
	 */
	public function setFloat(float $float): void
	{
		$this->float = $float;
	}

	/**
	 * @JsonAnnotation({"name":"string","nullable":false})
	 * @return string
	 */
	public function getString(): string
	{
		return isset($this->string) ? $this->string : '';
	}

	/**
	 * @JsonAnnotation({"name":"string","nullable":false})
	 * @param string $string
	 */
	public function setString(string $string): void
	{
		$this->string = $string;
	}

	/**
	 * @JsonAnnotation({"name":"booleanNull","nullable":true})
	 * @return bool|NULL
	 */
	public function isBooleanNull(): ?bool
	{
		return isset($this->booleanNull) ? $this->booleanNull : null;
	}

	/**
	 * @JsonAnnotation({"name":"booleanNull","nullable":true})
	 * @param bool|NULL $booleanNull
	 */
	public function setBooleanNull(?bool $booleanNull): void
	{
		$this->booleanNull = $booleanNull;
	}

	/**
	 * @JsonAnnotation({"name":"integerNull","nullable":true})
	 * @return int
	 */
	public function getIntegerNull(): ?int
	{
		return isset($this->integerNull) ? $this->integerNull : null;
	}

	/**
	 * @JsonAnnotation({"name":"integerNull","nullable":true})
	 * @param int|NULL $integerNull
	 */
	public function setIntegerNull(?int $integerNull): void
	{
		$this->integerNull = $integerNull;
	}

	/**
	 * @JsonAnnotation({"name":"floatNull","nullable":true})
	 * @return float
	 */
	public function getFloatNull(): ?float
	{
		return isset($this->floatNull) ? $this->floatNull : null;
	}

	/**
	 * @JsonAnnotation({"name":"floatNull","nullable":true})
	 * @param float|NULL $floatNull
	 */
	public function setFloatNull(?float $floatNull): void
	{
		$this->floatNull = $floatNull;
	}

	/**
	 * @JsonAnnotation({"name":"stringNull","nullable":true})
	 * @return string|NULL
	 */
	public function getStringNull(): ?string
	{
		return isset($this->stringNull) ? $this->stringNull : null;
	}

	/**
	 * @JsonAnnotation({"name":"stringNull","nullable":true})
	 * @param string|NULL $stringNull
	 */
	public function setStringNull(?string $stringNull): void
	{
		$this->stringNull = $stringNull;
	}
}

