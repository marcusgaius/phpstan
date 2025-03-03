<?php

namespace MarcusGaius\PHPStan\Reflection\Mixin;

use Craft;
use craft\base\Element;
use craft\behaviors\CustomFieldBehavior;
use craft\elements\db\ElementQuery;
use Exception;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Dummy\DummyMethodReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\CallableType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ThisType;
use PHPStan\Type\VerbosityLevel;
use Yii;

class CustomFieldMixinExtension implements MethodsClassReflectionExtension
{
	/** @var array<string, MethodReflection> */
	private array $cache = [];

	public function __construct(private ReflectionProvider $reflectionProvider)
	{
	}

	public function hasMethod(ClassReflection $classReflection, string $methodName): bool
	{
		if (array_key_exists($classReflection->getCacheKey() . '-' . $methodName, $this->cache)) {
			return true;
		}

		$methodReflection = $this->findMethod($classReflection, $methodName);

		if ($methodReflection !== null) {
			$this->cache[$classReflection->getCacheKey() . '-' . $methodName] = $methodReflection;

			return true;
		}

		return false;
	}

	public function getMethod(
		ClassReflection $classReflection,
		string $methodName,
	): MethodReflection {
		return $this->cache[$classReflection->getCacheKey() . '-' . $methodName];
	}

	/**
	 * @throws ShouldNotHappenException
	 */
	private function findMethod(ClassReflection $classReflection, string $methodName): MethodReflection|null
	{
		if (! $classReflection->is(ElementQuery::class)) {
			return null;
		}

		$relatedElement = $classReflection->getActiveTemplateTypeMap()->getType('TElement');

		if ($relatedElement === null) {
			return null;
		}

		if ($relatedElement->getObjectClassReflections() !== []) {
			$elementReflection = $relatedElement->getObjectClassReflections()[0];
		} else {
			$elementReflection = $this->reflectionProvider->getClass(Element::class);
		}

		if (!$elementReflection->is(Element::class)) {
			return null;
		}

		$behaviorType = new ObjectType(
			CustomFieldBehavior::class,
		);


		dump(
			$behaviorType->getObjectClassReflections(),
			//$behaviorType->getObjectClassReflections()[0]->hasProperty($methodName),
			//$behaviorType->getObjectClassReflections()[0]->getFileName(),
		);


		if (!$behaviorType->hasMethod($methodName)->yes()) {
			return null;
		}

		$reflection = $behaviorType->getMethod($methodName, new OutOfClassScope());

		$parametersAcceptor = $reflection->getVariants()[0];
		$returnType         = $parametersAcceptor->getReturnType();

		if ((new ObjectType(CustomFieldBehavior::class))->isSuperTypeOf($returnType)->yes()) {
			$returnType = new ThisType($classReflection);
		}

		return new CustomFieldMixinMethodReflection(
			$methodName,
			$reflection->getDeclaringClass(),
			$parametersAcceptor->getParameters(),
			$returnType,
			$parametersAcceptor->isVariadic(),
		);
	}

	private function getBehaviorName(string $elementClassName): string
	{
		$method = $this->reflectionProvider->getClass($elementClassName)->getNativeMethod('getBehavior');

		$returnType = $method->getVariants()[0]->getReturnType();

		if (in_array(CustomFieldBehavior::class, $returnType->getReferencedClasses(), true)) {
			return CustomFieldBehavior::class;
		}

		$classNames = $returnType->getObjectClassNames();

		if (count($classNames) === 1) {
			return $classNames[0];
		}

		return $returnType->describe(VerbosityLevel::value());
	}
}