<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

interface RepositoryInterface extends
    AgeRangeInterface,
    CalendarInterface,
    ContactPointInterface,
    DescriptionRepositoryInterface,
    EventProductionInterface,
    ImageInterface,
    LabelInterface,
    LocationInterface,
    NameInterface,
    OrganizerInterface,
    PriceInterface,
    ProductionInterface,
    RelationInterface,
    TargetAudienceInterface,
    ThemeRepositoryInterface,
    TypeRepositoryInterface
{
}
