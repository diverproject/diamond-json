<?php

namespace diamond\json;

class JsonExport
{
	private $includeOnly;
	private $excludeOnly;
	private $propertiesOnly;
	private $dateFormat;

	public function __construct()
	{
		$this->includeOnly = 0x00;
		$this->excludeOnly = 0x00;
		$this->dateFormat = JsonUtil::DATETIME_ZONE3_FORMAT;
		$this->propertiesOnly = JsonObject::getOnlyProperties() !== JsonObject::JSON_CLASS_VALIDATE;
	}

	public function getIncludeOnly(): int
	{
		return $this->includeOnly;
	}

	public function setIncludeOnly(int $includeOnly): void
	{
		$this->includeOnly = $includeOnly;
	}

	public function getExcludeOnly(): int
	{
		return $this->excludeOnly;
	}

	public function setExcludeOnly(int $excludeOnly): void
	{
		$this->excludeOnly = $excludeOnly;
	}

	public function isPropertiesOnly(): bool
	{
		return $this->propertiesOnly;
	}

	public function setPropertiesOnly(bool $propertiesOnly): void
	{
		$this->propertiesOnly = $propertiesOnly;
	}

	public function getDateFormat(): string
	{
		return $this->dateFormat;
	}

	public function setDateFormat(string $dateFormat): void
	{
		$this->dateFormat = $dateFormat;
	}
}

