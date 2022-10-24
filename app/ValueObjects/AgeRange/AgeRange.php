<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\ValueObjects\AgeRange;

class AgeRange
{
    private int $ageFrom;

    private int $ageTo;

    public function getAgeFrom(): int
    {
        return $this->ageFrom;
    }

    public function getAgeTo(): int
    {
        return $this->ageTo;
    }

    public function __toString(): string
    {
        return $this->ageFrom . '-' . $this->ageTo;
    }

    public function __construct(int $ageFrom, int $ageTo)
    {
        $this->ageFrom = $ageFrom;
        $this->ageTo = $ageTo;
    }
}
