<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Term;

class TermFactory implements TermFactoryInterface
{
    /**
     * @var array
     */
    private $terms;

    /**
     * TermFactory constructor.
     * @param $terms
     */
    public function __construct($terms)
    {
        $this->terms = $terms;
    }

    /**
     * @inheritdoc
     */
    public function mapTerm($kinepolisTeid)
    {
        return $this->terms[$kinepolisTeid];
    }
}
