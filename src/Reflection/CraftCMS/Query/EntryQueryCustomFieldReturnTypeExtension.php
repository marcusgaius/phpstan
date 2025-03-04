<?php
declare(strict_types=1);

namespace MarcusGaius\PHPStan\Reflection\CraftCMS\Query;

use craft\elements\db\EntryQuery;

class EntryQueryCustomFieldReturnTypeExtension extends ElementQueryCustomFieldReturnTypeExtension
{
	public function getClass(): string
	{
		return EntryQuery::class;
	}
}
