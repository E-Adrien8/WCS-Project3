<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class SexType extends AbstractEnumType
{
    final public const FEMALE = 'female';
    final public const MALE = 'male';

    protected static array $choices = [
        self::FEMALE => 'Femme',
        self::MALE => 'Homme',
    ];
}
