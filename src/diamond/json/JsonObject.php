<?php

namespace diamond\json;

use diamond\collection\Arrayable;
use diamond\lang\utils\GlobalFunctions;
use JsonSerializable;

class JsonObject implements Arrayable, JsonSerializable
{
	const JSON_NONE = 0;
	const JSON_ATTRIBUTES = 1;
	const JSON_CLASS_VALIDATE = 2;

	private static $onlyProperties = self::JSON_NONE;

	public function toArray($settings = false): array
	{
		$jsonExport = new JsonExport();
		$jsonExport->setPropertiesOnly(true);

		switch (self::$onlyProperties)
		{
			case self::JSON_ATTRIBUTES:
				$jsonExport->setPropertiesOnly(true);
				break;

			case self::JSON_CLASS_VALIDATE:
				$jsonExport->setPropertiesOnly(false);
				break;
		}

		if (is_bool($settings))
			$jsonExport->setPropertiesOnly($settings);

		else if (is_int($settings))
		{
			if ($settings > 0)
				$jsonExport->setIncludeOnly($settings);
			else if ($settings < 0)
				$jsonExport->setExcludeOnly($settings * -1);
		}

		else if ($settings instanceof JsonExport)
			$jsonExport = $settings;

		return JsonUtil::parseObject($this, $jsonExport);
	}

	public function fromArray(array $array): void
	{
		JsonUtil::parseArray($array, $this);
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}

	public function jsonUnserialize(string $json): void
	{
		$this->fromArray(json_decode($json, true));
	}

	public function __toString(): string
	{
		return $this->toJSON();
	}

	public static function getOnlyProperties(): int
	{
		return JsonObject::$onlyProperties;
	}

	public static function setOnlyProperties(int $onlyProperties): void
	{
		JsonObject::$onlyProperties = $onlyProperties;
	}
}

GlobalFunctions::load();
