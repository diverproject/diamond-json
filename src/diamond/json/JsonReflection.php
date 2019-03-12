<?php

namespace diamond\json;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use diamond\lang\StringParser;
use diamond\annotation\AnnotationParser;

class JsonReflection
{
	private $object;
	private $reflectionClass;

	public function __construct(object $object)
	{
		$this->object = $object;
		$this->reflectionClass = new ReflectionClass($object);
	}

	public function getObject(): object
	{
		return $this->object;
	}

	public function getReflectionClass(): ReflectionClass
	{
		return $this->reflectionClass;
	}

	public function getName(): string
	{
		return $this->reflectionClass->getName();
	}

	public function getClassName(): string
	{
		return nameOf($this->reflectionClass->getName());
	}

	/**
	 * @return JsonReflectionAttribute[]
	 */
	public function getReflectionAttributes(object $object): array
	{
		if ((($class_name = new ReflectionClass($object))->getName()) !== $this->getName())
			throw new JsonException(JsonException::JE_REFLECTION_CLASS_NAME, $this->getName(), $class_name);

		$reflectionAttributes = [];
		$reflectionClass = $this->reflectionClass;

		do {

			$this->parseReflectionMethods($object, $reflectionClass, $reflectionAttributes);

			foreach ($reflectionClass->getProperties() as $reflectionProperty)
				$this->parseReflectionProperty($object, $reflectionProperty, $reflectionAttributes);

		} while ($reflectionClass = $reflectionClass->getParentClass());

		return $reflectionAttributes;
	}

	private function parseReflectionProperty(object $object, ReflectionProperty $reflectionProperty, array &$reflectionAttributes): void
	{
		if ($reflectionProperty instanceof ReflectionProperty && !$reflectionProperty->isStatic())
		{
			$jsonReflectionAttribute = new JsonReflectionProperty($object, $reflectionProperty);
			$attribute = $jsonReflectionAttribute->getName();

			if ($this->reflectionClass->hasMethod(($name = sprintf('get%s', ucfirst($attribute)))))
				$jsonReflectionAttribute->setReflectionMethodGet($this->reflectionClass->getMethod($name));

			else if ($this->reflectionClass->hasMethod(($name = sprintf('has%s', ucfirst($attribute)))))
				$jsonReflectionAttribute->setReflectionMethodGet($this->reflectionClass->getMethod($name));

			else if ($this->reflectionClass->hasMethod(($name = sprintf('is%s', ucfirst($attribute)))))
				$jsonReflectionAttribute->setReflectionMethodGet($this->reflectionClass->getMethod($name));

			if ($this->reflectionClass->hasMethod(($name = sprintf('set%s', ucfirst($attribute)))))
				$jsonReflectionAttribute->setReflectionMethodSet($this->reflectionClass->getMethod($name));

			$jsonReflectionAttribute->initJsonAnnotation();
			$reflectionAttributes[$attribute] = $jsonReflectionAttribute;
		}
	}

	private function parseReflectionMethods(object $object, ReflectionClass $reflectionClass, array &$reflectionAttributes): void
	{
		foreach ($reflectionClass->getMethods() as $reflectionMethod)
			if ($reflectionMethod instanceof ReflectionMethod && !$reflectionMethod->isStatic())
			{
				$annotationMethod = AnnotationParser::parseMethod($reflectionMethod);

				foreach ($annotationMethod->getAnnotations() as $annotation)
				{
					if ($annotation instanceof JsonAnnotation && $annotation->getName() !== null)
					{
						if (isset($reflectionAttributes[$attribute = $annotation->getName()]))
							continue;

						$jsonAttribute = null;
						$method_name = $reflectionMethod->getName();

						// Ignore methods non-standard
						if (format('get%s', ucfirst($attribute)) !== $reflectionMethod->getName() &&
							format('set%s', ucfirst($attribute)) !== $reflectionMethod->getName() &&
							format('has%s', ucfirst($attribute)) !== $reflectionMethod->getName() &&
							format('is%s', ucfirst($attribute)) !== $reflectionMethod->getName())
							continue;

						if (StringParser::startsWith($method_name, 'get') ||
							StringParser::startsWith($method_name, 'has') ||
							StringParser::startsWith($method_name, 'is'))
						{
							$reflectionMethodSet = $reflectionClass->hasMethod($method_name_set = format('set%s', ucfirst($attribute))) ? $reflectionClass->getMethod($method_name_set) : null;
							$jsonAttribute = new JsonReflectionMethod($object, $reflectionMethod, $reflectionMethodSet);
						}

						if (StringParser::startsWith($method_name, 'set'))
						{
							if (($reflectionMethodGet = $reflectionClass->hasMethod($method_name_get = format('get%s', ucfirst($attribute)))) !== null ||
								($reflectionMethodGet = $reflectionClass->hasMethod($method_name_get = format('has%s', ucfirst($attribute)))) !== null ||
								($reflectionMethodGet = $reflectionClass->hasMethod($method_name_get = format('is%s', ucfirst($attribute)))) !== null )

							$attribute = $annotation->getName();
							$reflectionMethodGet = $reflectionClass->hasMethod($method_name_get) ? $this->reflectionClass->getMethod($method_name_get) : null;
							$jsonAttribute = new JsonReflectionMethod($object, $reflectionMethodGet, $reflectionMethod);
						}

						if ($attribute !== null && $jsonAttribute !== null)
						{
							$reflectionAttributes[$attribute] = $jsonAttribute;
							break;
						}
					}
				}
			}
	}
}

