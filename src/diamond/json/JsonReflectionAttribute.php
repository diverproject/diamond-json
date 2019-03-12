<?php

namespace diamond\json;

use diamond\annotation\AnnotationParser;

interface JsonReflectionAttribute
{
	public const JRA_NATIVE_TYPES = AnnotationParser::NATIVE_TYPES;
	public const JRA_NATIVE_BOOL = 1;
	public const JRA_NATIVE_INT = 2;
	public const JRA_NATIVE_FLOAT = 3;
	public const JRA_NATIVE_STRING = 4;
	public const JRA_NATIVE_ARRAY = 5;
	public const JRA_NATIVE_RESOURCE = 6;
	public const JRA_NATIVE_OBJECT = 7;

	public function getName(): string;
	public function getValue();
	public function setValue($var);
	public function getType(): int;
	public function getTypeName(): ?string;
	public function isIgnored(JsonExport $jsonExport): bool;
}

