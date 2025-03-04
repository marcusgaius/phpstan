<?php
declare(strict_types=1);

namespace MarcusGaius\PHPStan\Reflection\CraftCMS\Query;

use craft\elements\db\CategoryQuery;

class CategoryQueryCustomFieldReturnTypeExtension extends ElementQueryCustomFieldReturnTypeExtension
{
	public function getClass(): string
	{
		return CategoryQuery::class;
	}
}
