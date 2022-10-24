<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\ValueObjects\TargetAudience;

use ValueObjects\Enum\Enum;

class TargetAudience extends Enum
{
    public const EVERYONE = 0;
    public const MEMBERS = 1;
}
