<?php
declare(strict_types=1);

namespace MarcusGaius\PHPStan\Reflection\CraftCMS\Query;

use craft\elements\db\AddressQuery;

class AddressQueryCustomFieldReturnTypeExtension extends ElementQueryCustomFieldReturnTypeExtension
{
	public function getClass(): string
	{
		return AddressQuery::class;
	}
}
