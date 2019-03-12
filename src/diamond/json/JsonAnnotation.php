<?php

namespace diamond\json;

use diamond\annotation\CustomAnnotation;
use DateTime;

class JsonAnnotation extends CustomAnnotation
{
	private $name;
	private $format;
	private $nullable;
	private $default;
	private $exclude;
	private $include;

	public function __construct()
	{
		$this->setFormat(null);
		$this->setNullable(false);
		$this->setExclude(0);
		$this->setInclude(0);
	}

	public function parse(string $data, string $documentation, object $reflection): void
	{
		parent::parse($data, $documentation, $reflection);

		$this->load();
	}

	public function load(): void
	{
		$this->setExclude(0);
		$this->setInclude(0);
		$this->setNullable(true);

		if ($this->has('name')) $this->setName($this->get('name'));
		if ($this->has('type')) $this->setType($this->get('type'));
		if ($this->has('format')) $this->setFormat($this->get('format'));
		if ($this->has('default')) $this->setDefault($this->get('default'));
		if ($this->has('exclude')) $this->setExclude($this->get('exclude'));
		if ($this->has('include')) $this->setInclude($this->get('include'));
		if ($this->has('nullable')) $this->setNullable($this->get('nullable'));
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	protected function setName(?string $name): void
	{
		$this->name = $name;
	}

	public function getFormat(): ?string
	{
		return $this->format;
	}

	protected function setFormat(?string $format): void
	{
		$this->format = $format;
	}

	public function isNullable(): bool
	{
		return $this->nullable;
	}

	protected function setNullable(bool $nullable): void
	{
		$this->nullable = $nullable;
	}

	public function getDefault()
	{
		return $this->default;
	}

	protected function setDefault($default)
	{
		$this->default = $default;
	}

	public function getExclude(): int
	{
		return $this->exclude;
	}

	protected function setExclude(int $exclude): void
	{
		$this->exclude = $exclude;
	}

	public function getInclude(): int
	{
		return $this->include;
	}

	protected function setInclude(int $include): void
	{
		$this->include = $include;
	}

	public function getTypeName(): ?string
	{
		return $this->getType() !== null ? JsonUtil::parseAttributeTypeName(intval($this->getType())) : null;
	}

	public function parseAttribute(&$var, JsonReflectionAttribute $reflection, ?JsonExport $jsonExport): void
	{
		if ($var === null && !$this->isNullable())
		{
			if (($value = $this->getDefault()) !== null)
				$var = $this->parseDefaultValue($reflection, $value);
			else
				$var = $this->newDefaultValue($reflection);
		}

		if ($var !== null && $this->getFormat() !== null && $reflection->getType() !== JsonUtil::TYPE_OBJECT)
			$var = self::format($var, $reflection->getType(), $this->getFormat());
	}

	public static function parseDefaultValue(JsonReflectionAttribute $reflection, $var)
	{
		switch ($reflection->getType())
		{
			case JsonUtil::TYPE_CHAR:
				return JsonUtil::newChar($var);

			case JsonUtil::TYPE_STRING:
				return JsonUtil::newString($var);

			case JsonUtil::TYPE_BOOLEAN:
				return JsonUtil::newBoolean($var);

			case JsonUtil::TYPE_ARRAY:
				return ($array = JsonUtil::newArray($var)) !== null ? $array : [];

			case JsonUtil::TYPE_BYTE:
			case JsonUtil::TYPE_SHORT:
			case JsonUtil::TYPE_INT:
			case JsonUtil::TYPE_LONG:
				return JsonUtil::newInt($var);

			case JsonUtil::TYPE_FLOAT:
			case JsonUtil::TYPE_DOUBLE:
				return JsonUtil::newFloat($var);

			case JsonUtil::TYPE_DATETIME:
				return JsonUtil::newDateTime($var);

			case JsonUtil::TYPE_OBJECT:
				return JsonUtil::newObject($var);
		}

		throw new JsonException(JsonException::JE_UNKNOW_TYPE_NATIVE, $reflection->getType());
	}

	public static function newDefaultValue(JsonReflectionAttribute $reflection)
	{
		switch ($reflection->getType())
		{
			case JsonUtil::TYPE_CHAR:
				return chr(0);

			case JsonUtil::TYPE_STRING:
				return '';

			case JsonUtil::TYPE_BOOLEAN:
				return false;

			case JsonUtil::TYPE_ARRAY:
				return [];

			case JsonUtil::TYPE_BYTE:
			case JsonUtil::TYPE_SHORT:
			case JsonUtil::TYPE_INT:
			case JsonUtil::TYPE_LONG:
				return 0;

			case JsonUtil::TYPE_FLOAT:
			case JsonUtil::TYPE_DOUBLE:
				return 0.0;

			case JsonUtil::TYPE_DATETIME:
				return new DateTime();

			case JsonUtil::TYPE_OBJECT:
				if (class_exist($class_name = $reflection->getTypeName()))
					return new $class_name();
			throw new JsonException(JsonException::JE_UNKNOW_CLASS_NAME, $class_name);
		}

		throw new JsonException(JsonException::JE_UNKNOW_TYPE_DEFAULT, $reflection->getType());
	}

	public static function format($var, int $type, string $format): string
	{
		switch ($type)
		{
			case JsonUtil::TYPE_BOOLEAN:
				$format = explode(';', $format);
				return $var === true || count($format) === 1 ? $format[0] : $format[1];

			case JsonUtil::TYPE_DATETIME:
				return $var->format($format);

			case JsonUtil::TYPE_BYTE:
			case JsonUtil::TYPE_SHORT:
			case JsonUtil::TYPE_INT:
			case JsonUtil::TYPE_LONG:
			case JsonUtil::TYPE_FLOAT:
			case JsonUtil::TYPE_DOUBLE:
			case JsonUtil::TYPE_STRING:
				return sprintf("$format", $var);
		}

		return $var;
	}
}

