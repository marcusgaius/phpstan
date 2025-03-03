<?php

declare(strict_types=1);

namespace MarcusGaius\PHPStan\Reflection\CraftCMS\Query;

use craft\elements\db\AssetQuery;

class AssetQueryCustomFieldReturnTypeExtension extends ElementQueryCustomFieldReturnTypeExtension
{
	public function getClass(): string
	{
		return AssetQuery::class;
	}
}
