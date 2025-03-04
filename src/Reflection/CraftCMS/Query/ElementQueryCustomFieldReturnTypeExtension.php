<?php
declare(strict_types=1);

namespace MarcusGaius\PHPStan\Reflection\CraftCMS\Query;

use craft\behaviors\CustomFieldBehavior;
use craft\elements\db\ElementQueryInterface;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class ElementQueryCustomFieldReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
	public function getClass(): string
	{
		return ElementQueryInterface::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		if (!$methodReflection->getDeclaringClass()->is(CustomFieldBehavior::class)) {
			return false;
		}

		if (!$methodReflection->getDeclaringClass()->hasProperty($methodReflection->getName())) {
			return false;
		}

		return true;
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?Type
	{
		$regularReturnType = ParametersAcceptorSelector::selectFromArgs(
			$scope,
			$methodCall->getArgs(),
			$methodReflection->getVariants()
		)->getReturnType();

		if (!in_array(CustomFieldBehavior::class, $regularReturnType->getObjectClassNames())) {
			return null;
		}

		return new ObjectType($this->getClass());
	}
}
