<?php

namespace App\ORM\Type;

use App\Core\Ads\Domain\AdId;

class AdIdType extends AbstractUuidType
{
    const AD_ID = 'ad_id';

    public function getName(): string
    {
        return static::AD_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return AdId::class;
    }
}
