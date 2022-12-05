<?php

namespace CultuurNet\TransformEntryStore\Repository;

use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreAgeRangeDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreCalendarDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreContactPointDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreDescriptionDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreEventProductionDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreImageDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreLabelDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreLocationDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreNameDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreOrganizerDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StorePriceDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreProductionDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreRelationDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreTargetAudienceDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreThemeDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreTypeDBALRepository;
use CultuurNet\TransformEntryStore\Stores\StoreRepository;

final class RepositoryServiceProvider extends AbstractServiceProvider
{
    protected function getProvidedServiceNames(): array
    {
        return [StoreRepository::class];
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            StoreRepository::class,
            fn() => new StoreRepository(
                new StoreAgeRangeDBALRepository(
                    $container->get('dbal_connection'),
                    'age_range'
                ),
                new StoreCalendarDBALRepository(
                    $container->get('dbal_connection'),
                    'calendar'
                ),
                new StoreContactPointDBALRepository(
                    $container->get('dbal_connection'),
                    'contact_point'
                ),
                new StoreDescriptionDBALRepository(
                    $container->get('dbal_connection'),
                    'description'
                ),
                new StoreEventProductionDBALRepository(
                    $container->get('dbal_connection'),
                    'event_production'
                ),
                new StoreImageDBALRepository(
                    $container->get('dbal_connection'),
                    'image'
                ),
                new StoreLabelDBALRepository(
                    $container->get('dbal_connection'),
                    'label'
                ),
                new StoreLocationDBALRepository(
                    $container->get('dbal_connection'),
                    'location'
                ),
                new StoreNameDBALRepository(
                    $container->get('dbal_connection'),
                    'name'
                ),
                new StoreOrganizerDBALRepository(
                    $container->get('dbal_connection'),
                    'organizer'
                ),
                new StorePriceDBALRepository(
                    $container->get('dbal_connection'),
                    'price_info'
                ),
                new StoreProductionDBALRepository(
                    $container->get('dbal_connection'),
                    'production'
                ),
                new StoreRelationDBALRepository(
                    $container->get('dbal_connection'),
                    'relation'
                ),
                new StoreTargetAudienceDBALRepository(
                    $container->get('dbal_connection'),
                    'target_audience'
                ),
                new StoreThemeDBALRepository(
                    $container->get('dbal_connection'),
                    'theme'
                ),
                new StoreTypeDBALRepository(
                    $container->get('dbal_connection'),
                    'type'
                )
            )
        );
    }
}