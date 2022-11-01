<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class MealTimeType extends AbstractEnumType
{
    final public const LUNCH = 'lunch';
    final public const DINNER = 'dinner';

    protected static array $choices = [
        self::LUNCH => 'Déjeuner',
        self::DINNER => 'Dîner',
    ];
}
