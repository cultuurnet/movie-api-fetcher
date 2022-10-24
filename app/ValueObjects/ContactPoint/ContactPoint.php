<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\ValueObjects\ContactPoint;

use ValueObjects\ValueObjectInterface;

class ContactPoint implements ValueObjectInterface
{
    private ContactPointType $contactPointType;

    /**
     * Returns a object taking PHP native value(s) as argument(s).
     *
     * @return ValueObjectInterface
     */
    public static function fromNative()
    {
        // TODO: Implement fromNative() method.
        return new self(ContactPointType::fromNative('url'));
    }

    /**
     * Compare two ValueObjectInterface and tells whether they can be considered equal
     *
     * @return bool
     */
    public function sameValueAs(ValueObjectInterface $object)
    {
        // TODO: Implement sameValueAs() method.
        return false;
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return '';
    }

    public function __construct(ContactPointType $contactPointType)
    {
        $this->contactPointType = $contactPointType;
    }

    public function getContactPointType(): ContactPointType
    {
        return $this->contactPointType;
    }
}
