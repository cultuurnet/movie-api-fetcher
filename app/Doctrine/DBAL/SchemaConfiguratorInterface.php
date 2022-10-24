<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Doctrine\DBAL;

use Doctrine\DBAL\Schema\AbstractSchemaManager;

interface SchemaConfiguratorInterface
{
    public function configure(AbstractSchemaManager $schemaManager): void;
}
