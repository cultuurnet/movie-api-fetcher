<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\ValueObjects\ContactPoint;

use ValueObjects\Enum\Enum;

class ContactPointType extends enum
{
    public const URL = 'url';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
}
