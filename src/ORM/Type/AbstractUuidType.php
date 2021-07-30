<?php

namespace App\ORM\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

abstract class AbstractUuidType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $fqcn = $this->getValueObjectClassName();

        if (is_scalar($value)) {
            $value = new $fqcn($value);
        }

        if (!is_a($value, $fqcn)) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $fqcn);
        }

        return $value;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $fieldDeclaration['length'] = 16;
        $fieldDeclaration['fixed'] = true;

        return $platform->getBinaryTypeDeclarationSQL($fieldDeclaration);
    }

    abstract protected function getValueObjectClassName(): string;
}