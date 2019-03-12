<?php

namespace diamond\json;

use diamond\annotation\AbstractAnnotation;
use diamond\annotation\AnnotationParser;
use diamond\annotation\ReturnAnnotation;
use diamond\lang\Bitwise;
use ReflectionMethod;
use TypeError;

class JsonReflectionMethod implements JsonReflectionAttribute
{
	private $object;
	private $type;
	private $typeName;
	private $reflectionMethodGet;
	private $reflectionMethodSet;
	private $jsonAnnotation;

	public function __construct(object $object, ?ReflectionMethod $reflectionMethodGet, ?ReflectionMethod $reflectionMethodSet)
	{
		$this->type = JsonUtil::TYPE_NONE;
		$this->object = $object;
		$this->reflectionMethodGet = $reflectionMethodGet;
		$this->reflectionMethodSet = $reflectionMethodSet;
		$this->init();
	}

	public function getObject(): object
	{
		return $this->object;
	}

	public function getReflectionMethodGet(): ?ReflectionMethod
	{
		return $this->reflectionMethodGet;
	}

	public function setReflectionMethodGet(?ReflectionMethod $reflectionMethodGet): void
	{
		$this->reflectionMethodGet = $reflectionMethodGet;
	}

	public function getReflectionMethodSet(): ?ReflectionMethod
	{
		return $this->reflectionMethodSet;
	}

	public function setReflectionMethodSet(?ReflectionMethod $reflectionMethodSet): void
	{
		$this->reflectionMethodSet = $reflectionMethodSet;
	}

	public function getJsonAnnotation(): ?JsonAnnotation
	{
		return $this->jsonAnnotation;
	}

	public function setJsonAnnotation(?JsonAnnotation $jsonAnnotation): void
	{
		$this->jsonAnnotation = $jsonAnnotation;
	}

	public function getName(): string
	{
		return $this->getJsonAnnotation()->getName();
	}

	public function getValue(JsonExport $jsonExport = null)
	{
		$value = null;

		if ($this->getReflectionMethodGet() !== null)
		{
			try {
				$value = $this->getReflectionMethodGet()->invoke($this->object);
			} catch (TypeError $e) {
			}
		}

		$this->getJsonAnnotation()->parseAttribute($value, $this, $jsonExport);

		return $value;
	}

	public function setValue($value): void
	{
		$value = JsonUtil::parseValue($value, $this->getType(), $this->getTypeName());

		if ($this->getReflectionMethodSet() !== null)
		{
			$this->getReflectionMethodSet()->invoke($this->object, $value);
			$this->getReflectionMethodSet()->setAccessible(false);
		}
	}

	public function getType(): int
	{
		return $this->type;
	}

	public function getTypeName(): ?string
	{
		return $this->typeName;
	}

	private function initSetType(AbstractAnnotation $annotation): void
	{
		$class_name = AnnotationParser::getFirstMultiType($annotation->getType(), false);
		$this->type = JsonUtil::parseAttributeType($class_name);
		$this->typeName = JsonUtil::parseAttributeTypeName($this->type);

		if ($this->typeName === 'object')
			$this->typeName = $class_name;
	}

	private function init(): void
	{
		// Find type by method annotation if not found on property annotation
		if ($this->type === JsonUtil::TYPE_NONE)
		{
			if ($this->reflectionMethodGet !== null)
			{
				$annotationMethod = AnnotationParser::parseMethod($this->reflectionMethodGet);

				foreach ($annotationMethod->getAnnotations() as $annotation)
					if ($annotation instanceof ReturnAnnotation)
					{
						$this->initSetType($annotation);
						break;
					}
			}

			if ($this->reflectionMethodSet !== null)
			{
				$annotationMethod = AnnotationParser::parseMethod($this->reflectionMethodSet);

				if (count($annotationMethod->getParameterAnnotations()) > 0)
				{
					$annotations = $annotationMethod->getParameterAnnotations();
					reset($annotations);
					$annotation = current($annotationMethod->getParameterAnnotations());
					$this->initSetType($annotation);
				}
			}
		}

		// Find json preferences for export data by method and override property
		if ($this->reflectionMethodGet !== null)
		{
			$annotationMethodGet = AnnotationParser::parseMethod($this->reflectionMethodGet);

			foreach ($annotationMethodGet->getAnnotations() as $annotation)
				if ($annotation instanceof JsonAnnotation)
				{
					$this->setJsonAnnotation($annotation);
					break;
				}
		}

		// Find json preferences for import data by method and override property
		else if ($this->reflectionMethodSet !== null)
		{
			$annotationMethodSet = AnnotationParser::parseMethod($this->reflectionMethodSet);

			foreach ($annotationMethodSet->getAnnotations() as $annotation)
				if ($annotation instanceof JsonAnnotation)
				{
					$this->setJsonAnnotation($annotation);
					break;
				}
		}
	}

	public function isIgnored(JsonExport $jsonExport): bool
	{
		$jsonAnnotation = $this->getJsonAnnotation();

		// Exclude ignore only who was market to be excluded
		if ($jsonExport->getExcludeOnly() > 0)
			return	$this->getJsonAnnotation() !== null &&
					Bitwise::hasPropertie($jsonExport->getExcludeOnly(), $jsonAnnotation->getExclude());

		// Include ignore only who wasn't maker to be included
		if ($jsonExport->getIncludeOnly() > 0)
			return	$jsonAnnotation === null ||
					$jsonAnnotation->getInclude() === 0 ||
					!Bitwise::hasPropertie($jsonExport->getIncludeOnly(), $this->getJsonAnnotation()->getInclude());

		return false;
	}
}

