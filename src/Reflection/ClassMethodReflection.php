<?php
declare(strict_types=1);

namespace MarcusGaius\PHPStan\Reflection;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;

abstract class ClassMethodReflection implements MethodReflection
{
	public function __construct(
		protected MethodReflection $methodReflection,
	) {
	}

	public function getDeclaringClass(): ClassReflection
	{
		return $this->methodReflection->getDeclaringClass();
	}

	public function isStatic(): bool
	{
		return $this->methodReflection->isStatic();
	}

	public function isPrivate(): bool
	{
		return $this->methodReflection->isPrivate();
	}

	public function isPublic(): bool
	{
		return $this->methodReflection->isPublic();
	}

	public function getDocComment(): ?string
	{
		return $this->methodReflection->getDocComment();
	}

	public function getName(): string
	{
		return $this->methodReflection->getName();
	}

	public function getPrototype(): ClassMemberReflection
	{
		return $this->methodReflection->getPrototype();
	}

	public function getVariants(): array
	{
		return $this->methodReflection->getVariants();
	}

	public function isDeprecated(): TrinaryLogic
	{
		return $this->methodReflection->isDeprecated();
	}

	public function getDeprecatedDescription(): ?string
	{
		return $this->methodReflection->getDeprecatedDescription();
	}

	public function isFinal(): TrinaryLogic
	{
		return $this->methodReflection->isFinal();
	}

	public function isInternal(): TrinaryLogic
	{
		return $this->methodReflection->isInternal();
	}

	public function getThrowType(): ?Type
	{
		return $this->methodReflection->getThrowType();
	}

	public function hasSideEffects(): TrinaryLogic
	{
		return $this->methodReflection->hasSideEffects();
	}
}