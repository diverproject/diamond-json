<?php

namespace diamond\json;

use ReflectionMethod;
use ReflectionProperty;
use TypeError;
use diamond\annotation\AbstractAnnotation;
use diamond\annotation\AnnotationParser;
use diamond\annotation\ReturnAnnotation;
use diamond\annotation\VarAnnotation;
use diamond\lang\Bitwise;

class JsonReflectionProperty implements JsonReflectionAttribute
{
	private $object;
	private $type;
	private $typeName;
	private $reflectionProperty;
	private $reflectionMethodSet;
	private $reflectionMethodGet;
	private $jsonAnnotation;

	public function __construct(object $object, ReflectionProperty $reflectionProperty)
	{
		$this->type = JsonUtil::TYPE_NONE;
		$this->object = $object;
		$this->reflectionProperty = $reflectionProperty;
		$this->init();
	}

	public function getObject(): object
	{
		return $this->object;
	}

	public function getReflectionProperty(): ReflectionProperty
	{
		return $this->reflectionProperty;
	}

	public function getReflectionMethodSet(): ?ReflectionMethod
	{
		return $this->reflectionMethodSet;
	}

	public function setReflectionMethodSet(?ReflectionMethod $reflectionMethodSet): void
	{
		$this->reflectionMethodSet = $reflectionMethodSet;
	}

	public function getReflectionMethodGet(): ?ReflectionMethod
	{
		return $this->reflectionMethodGet;
	}

	public function setReflectionMethodGet(?ReflectionMethod $reflectionMethodGet): void
	{
		$this->reflectionMethodGet = $reflectionMethodGet;
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
		if ($this->getJsonAnnotation() !== null && $this->getJsonAnnotation()->getName() !== null)
			return $this->getJsonAnnotation()->getName();

		return $this->reflectionProperty->getName();
	}

	public function getValue(JsonExport $jsonExport = null)
	{
		if ($this->getReflectionMethodGet() !== null)
		{
			try {
				$value = $this->getReflectionMethodGet()->invoke($this->object);
			} catch (TypeError $e) {
				$value = null;
			}
		}

		else
		{
			$this->getReflectionProperty()->setAccessible(true);
			$value = $this->getReflectionProperty()->getValue($this->object);
			$this->getReflectionProperty()->setAccessible(false);
		}

		if ($this->getJsonAnnotation() !== null)
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
		else
			$this->getReflectionProperty()->setValue($this->object, $value);
	}

	public function getType(): int
	{
		return $this->type;
	}

	public function getTypeName(): ?string
	{
		return $this->typeName;
	}

	private function init(): void
	{
		$annotationProperty = AnnotationParser::parseProperty($this->reflectionProperty);

		// Find type by property annotation
		foreach ($annotationProperty->getAnnotations() as $annotation)
			if ($annotation instanceof VarAnnotation)
			{
				$this->initSetType($annotation);
				break;
			}
	}

	private function initSetType(AbstractAnnotation $annotation): void
	{
		$class_name = AnnotationParser::getFirstMultiType($annotation->getType(), false);
		$this->type = JsonUtil::parseAttributeType($class_name);
		$this->typeName = JsonUtil::parseAttributeTypeName($this->type);

		if ($this->typeName === 'object')
			$this->typeName = $class_name;
	}

	public function initJsonAnnotation(): void
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

		$annotationProperty = AnnotationParser::parseProperty($this->reflectionProperty);

		// Find json preferences for export data by property
		foreach ($annotationProperty->getAnnotations() as $annotation)
			if ($annotation instanceof JsonAnnotation)
			{
				$this->setJsonAnnotation($annotation);
				break;
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

