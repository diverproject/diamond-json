<?php

namespace test\diamond\json;

use diamond\json\JsonObject;
use DateTime;

class ExampleObjectTypes extends JsonObject
{
	/**
	 * @var bool
	 */
	private $boolean;
	/**
	 * @var string
	 */
	private $string;
	/**
	 * @var int
	 */
	private $integer;
	/**
	 * @var float
	 */
	private $float;
	/**
	 * @var array
	 */
	private $array;
	/**
	 * @var DateTime
	 */
	private $datetime;
	/**
	 * @var ExampleObjectTypes
	 */
	private $self;
	private $unknow;
	private $method;

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
	 * @return array
	 */
	public function getArray(): array
	{
		return $this->array;
	}

	/**
	 * @param array $array
	 */
	public function setArray(array $array)
	{
		$this->array = $array;
	}

	/**
	 * @return DateTime
	 */
	public function getDatetime(): DateTime
	{
		return $this->datetime;
	}

	/**
	 * @param DateTime $datetime
	 */
	public function setDatetime(DateTime $datetime)
	{
		$this->datetime = $datetime;
	}

	public function getSelf(): ?ExampleObjectTypes
	{
		return $this->self;
	}

	public function setSelf(?ExampleObjectTypes $self): void
	{
		$this->self = $self;
	}

	/**
	 * @return ExampleObjectTypes
	 */
	public function getUnknow(): ?ExampleObjectTypes
	{
		return $this->unknow;
	}

	public function setUnknow(?ExampleObjectTypes $unknow): void
	{
		$this->unknow = $unknow;
	}

	public function getMethod(): ?ExampleObjectTypes
	{
		return $this->method;
	}

	/**
	 * @JsonMethod("nullable":false)
	 * @param NULL|ExampleObjectTypes $method a ExampleObjectTypes object
	 */
	public function setMethod(?ExampleObjectTypes $method): void
	{
		$this->method = $method;
	}
}

