<?php

declare(strict_types=1);

namespace MarcusGaius\PHPStan\Reflection\CraftCMS\Query;

use craft\elements\db\GlobalSetQuery;

class GlobalSetQueryCustomFieldReturnTypeExtension extends ElementQueryCustomFieldReturnTypeExtension
{
	public function getClass(): string
	{
		return GlobalSetQuery::class;
	}
}
