<?php

namespace diamond\json;

class JsonException extends \Exception
{
	private static $customMessages = [];

	public const JE_REFLECTION_CLASS_NAME = 1;
	public const JE_PARSE_VALUE_PARAM = 2;
	public const JE_PARSE_VALUE_TYPE = 3;
	public const JE_PARSE_VALUE_DATETIME = 4;
	public const JE_PARSE_VALUE_CLASSNAME = 5;
	public const JE_PARSE_VALUE_OBJECT = 6;
	public const JE_PARSE_VALUE_NATIVE = 7;
	public const JE_UNKNOW_CLASS_NAME = 8;
	public const JE_UNKNOW_TYPE_DEFAULT = 9;
	public const JE_UNKNOW_TYPE_NATIVE = 10;

	public function __construct(int $code)
	{
		$args = array_slice(func_get_args(), 1);
		$previous = end($args) instanceof \Throwable ? array_pop($args) : null;
		$format = self::getDefaultMessage($code);
		array_unshift($args, $format);
		$message = format($args);

		parent::__construct($message, $code, $previous);
	}

	public static function getDefaultMessage(int $code): string
	{
		if (isset(self::$customMessages[$code]))
			return self::$customMessages[$code];

		switch ($code)
		{
			case self::JE_REFLECTION_CLASS_NAME: return 'invalid reflection object (expected: %s, received: %s)';
			case self::JE_PARSE_VALUE_PARAM: return 'failed to parse a value by insufficient type data';
			case self::JE_PARSE_VALUE_TYPE: return 'failed to parse a value by unknow type (type: %d, typeName: %s)';
			case self::JE_PARSE_VALUE_DATETIME: return 'failed to parse a datetime (var: %s)';
			case self::JE_PARSE_VALUE_CLASSNAME: return 'failed to parse a new object by not setted "'.JsonUtil::CLASS_NAME_FIELD.'"';
			case self::JE_PARSE_VALUE_OBJECT: return 'failed to parse a new object (type: %s)';
			case self::JE_PARSE_VALUE_NATIVE: return 'failed to parse a native value (expected: string|int, received: %s)';
			case self::JE_UNKNOW_CLASS_NAME: return 'failed to instance a new object by unknow class (class: %s)';
			case self::JE_UNKNOW_TYPE_DEFAULT: return 'failed to get a default value by unknow type (type: %d)';
			case self::JE_UNKNOW_TYPE_NATIVE: return 'failed to get a native value by unknow type (type: %d)';
		}
	}

	public static function getCustomMessages(): array
	{
		return self::$customMessages;
	}

	public static function getCustomMessage(int $code): ?string
	{
		return isset(self::$customMessages[$code]) ? self::$customMessages[$code] : null;
	}

	public static function setCustomMessages(array $customMessages): void
	{
		self::$customMessages = $customMessages;
	}

	public static function setCustomMessage(int $code, string $customMessage): void
	{
		self::$customMessages = self::$customMessages[$code] = $customMessage;
	}
}

