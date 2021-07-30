<?php

namespace App\ORM\Type;

use App\Core\Ads\Domain\CarModelId;

class CarModelIdType extends AbstractUuidType
{
    const CAR_MODEL_ID = 'car_model_id';

    public function getName(): string
    {
        return static::CAR_MODEL_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return CarModelId::class;
    }
}