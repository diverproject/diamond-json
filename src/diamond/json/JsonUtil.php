<?php

namespace diamond\json;

use diamond\annotation\AnnotationParser;
use diamond\lang\BoolParser;
use diamond\lang\FloatParser;
use diamond\lang\IntParser;
use diamond\lang\StringParser;
use DateTime;

class JsonUtil
{
	public const TYPE_NONE = 0;
	public const TYPE_STRING = 1;
	public const TYPE_CHAR = 2;
	public const TYPE_BOOLEAN = 3;
	public const TYPE_ARRAY = 4;
	public const TYPE_BYTE = 5;
	public const TYPE_SHORT =6;
	public const TYPE_INT = 7;
	public const TYPE_LONG = 8;
	public const TYPE_FLOAT = 9;
	public const TYPE_DOUBLE = 10;
	public const TYPE_DATETIME = 11;
	public const TYPE_OBJECT = 12;

	public const DATE_FORMAT = 'Y-m-d';
	public const DATETIME_FORMAT = 'Y-m-d H:i:s';
	public const DATETIME_ZONE1_FORMAT = 'Y-m-d H:i:s O';
	public const DATETIME_ZONE2_FORMAT = 'Y-m-d H:i:s T';
	public const DATETIME_ZONE3_FORMAT = 'Y-m-d H:i:s e';
	public const DATETIME_ALL_FORMATS = [
		self::DATE_FORMAT,
		self::DATETIME_FORMAT,
		self::DATETIME_ZONE1_FORMAT,
		self::DATETIME_ZONE2_FORMAT,
		self::DATETIME_ZONE3_FORMAT,
	];
	public const CLASS_NAME_FIELD = 'className';

	public static function parseObject(?object $object, JsonExport $jsonExport): ?array
	{
		if ($object === null)
			return null;

		$data = [
			self::CLASS_NAME_FIELD => get_class($object),
		];
		$jsonReflection = new JsonReflection($object);
		$jsonReflectionAttributes = $jsonReflection->getReflectionAttributes($object);

		foreach ($jsonReflectionAttributes as $jsonReflectionAttribute)
			if ($jsonReflectionAttribute instanceof JsonReflectionAttribute)
			{
				if ($jsonReflectionAttribute->isIgnored($jsonExport))
					continue;

				$value = $jsonReflectionAttribute->getValue($jsonExport);

				if ($jsonReflectionAttribute->getType() === self::TYPE_OBJECT)
					$value = self::parseObject($value, $jsonExport);

				$data[$jsonReflectionAttribute->getName()] = $value;
			}

		return $data;
	}

	public static function parseArray(array $array, JsonObject $object = null): void
	{
		$jsonReflection = new JsonReflection($object);
		$jsonReflectionAttributes = $jsonReflection->getReflectionAttributes($object);

		foreach ($jsonReflectionAttributes as $jsonReflectionAttribute)
			if ($jsonReflectionAttribute instanceof JsonReflectionAttribute)
			{
				if (!array_key_exists($attribute = $jsonReflectionAttribute->getName(), $array))
					continue;

				$jsonReflectionAttribute->setValue($array[$attribute]);
			}
	}

	public static function parseAttributeType(string $typeName): ?int
	{
		switch (strtolower($typeName))
		{
			case 'char':
				return self::TYPE_CHAR;

			case 'string':
				return self::TYPE_STRING;

			case 'byte':
			case 'tinyint':
				return self::TYPE_BYTE;

			case 'short':
			case 'smallint':
				return self::TYPE_SHORT;

			case 'int':
			case 'integer':
			case 'number':
				return self::TYPE_INT;

			case 'long':
			case 'bigint':
				return self::TYPE_LONG;

			case 'float':
			case 'decimal':
				return self::TYPE_FLOAT;

			case 'double':
				return self::TYPE_DOUBLE;

			case 'array':
				return self::TYPE_ARRAY;

			case 'bool':
			case 'boolean':
				return self::TYPE_BOOLEAN;

			case 'datetime':
				return self::TYPE_DATETIME;
		}

		if (StringParser::endsWith('[]', $typeName))
			return self::TYPE_ARRAY;

		return class_exists($typeName) ? self::TYPE_OBJECT : null;
	}

	public static function parseValue($var, ?int $type, ?string $typeName)
	{
		if ($type === null && $typeName === null)
			throw new JsonException(JsonException::JE_PARSE_VALUE_PARAM);

		if ($var === null)
			return null;

		if ($type === null)
			$type = self::parseAttributeType($typeName);

		if ($typeName === null)
			$typeName = self::parseAttributeTypeName($type);

		if (is_object($var) && $type !== self::TYPE_OBJECT)
			throw new JsonException(JsonException::JE_PARSE_VALUE_NATIVE, $typeName, nameOf($var));

		switch ($type)
		{
			case self::TYPE_NONE:
				return is_array($var) ? self::newArray($var) : $var;

			case self::TYPE_CHAR:		return self::newChar($var);
			case self::TYPE_STRING:		return self::newString($var);

			case self::TYPE_BYTE:
			case self::TYPE_SHORT:
			case self::TYPE_INT:
			case self::TYPE_LONG:
				return self::newInt($var);

			case self::TYPE_FLOAT:
			case self::TYPE_DOUBLE:
				return self::newFloat($var);

			case self::TYPE_ARRAY:		return self::newArray($var);
			case self::TYPE_BOOLEAN:	return self::newBoolean($var);
			case self::TYPE_DATETIME:	return self::newDateTime($var);
			case self::TYPE_OBJECT:		return self::newObject($var, $typeName);
		}

		throw new JsonException(JsonException::JE_PARSE_VALUE_TYPE, $type, $typeName);
	}

	public static function newChar($var): string
	{
		return !is_string($var) ? ($var = strval($var)){0} : $var{0};
	}

	public static function newString($var)
	{
		return !is_string($var) ? strval($var) : $var;
	}

	public static function newInt($var)
	{
		return IntParser::parseInteger($var);
	}

	public static function newFloat($var)
	{
		return FloatParser::parseFloat($var);
	}

	public static function newArray($var)
	{
		return is_array($var) ? $var : null;
	}

	public static function newBoolean($var)
	{
		return BoolParser::parseBool($var);
	}

	public static function newDateTime($var)
	{
		if ($var instanceof DateTime)
			return $var;

		if (IntParser::isInteger($var))
			return new DateTime("@$var");

		foreach (self::DATETIME_ALL_FORMATS as $format)
		{
			$array = date_parse_from_format($format, $var);

			if ($array['warning_count'] === 0 && $array['error_count'] === 0)
				return new DateTime($var);
		}

		throw new JsonException(JsonException::JE_PARSE_VALUE_DATETIME, $var);
	}

	public static function newObject($var, ?string $class_name = null)
	{
		if (is_object($var))
			return $var;

		if (is_array($var))
		{
			if (!isset($var[self::CLASS_NAME_FIELD]) && $class_name === null)
				throw new JsonException(JsonException::JE_PARSE_VALUE_CLASSNAME);

			if (isset($var[self::CLASS_NAME_FIELD]))
				$class_name = $var[self::CLASS_NAME_FIELD];

			$object = new $class_name();
			self::parseArray($var, $object);

			return $object;
		}

		throw new JsonException(JsonException::JE_PARSE_VALUE_DATETIME, gettype($var));
	}

	public static function parseAttributeTypeName(int $type): ?string
	{
		switch (strtolower($type))
		{
			case self::TYPE_CHAR:		return 'char';
			case self::TYPE_STRING:		return 'string';
			case self::TYPE_BYTE:		return 'byte';
			case self::TYPE_SHORT:		return 'short';
			case self::TYPE_INT:		return 'int';
			case self::TYPE_LONG:		return 'long';
			case self::TYPE_FLOAT:		return 'float';
			case self::TYPE_DOUBLE:		return 'double';
			case self::TYPE_ARRAY:		return 'array';
			case self::TYPE_BOOLEAN:	return 'bool';
			case self::TYPE_DATETIME:	return DateTime::class;
			case self::TYPE_OBJECT:		return 'object';
		}

		return null;
	}
}

AnnotationParser::registerClassName(JsonAnnotation::class);
