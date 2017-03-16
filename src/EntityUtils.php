<?php

namespace WebservicesNl\Utils;

/**
 * Class EntityUtils.
 */
class EntityUtils
{
    /**
     * Extract the value of a property from an entity.
     *
     * @param mixed  $entity
     * @param string $property
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public static function extractValueFromEntity($entity, $property)
    {
        $getter = self::createGetter($entity, $property);

        return $entity->$getter();
    }

    /**
     * @param mixed  $entity
     * @param string $property
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public static function createGetter($entity, $property)
    {
        $prefixes = ['get', 'is', 'has'];
        foreach ($prefixes as $prefix) {
            $getter = $prefix . ucfirst($property);
            if (is_callable([$entity, $getter])) {
                return $getter;
            }
        }

        if (is_callable([$entity, $property])) {
            return $property;
        }

        throw new \InvalidArgumentException(
            sprintf('No getter found for property %s in class %s', $property, get_class($entity))
        );
    }

    /**
     * @param mixed  $entity
     * @param string $property
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException
     */
    public static function setValueInEntity($entity, $property, $value)
    {
        $setter = self::createSetter($entity, $property);
        $entity->$setter($value);
    }

    /**
     * @param mixed  $entity
     * @param string $property
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public static function createSetter($entity, $property)
    {
        $setter = 'set' . ucfirst($property);
        if (false === is_callable([$entity, $setter])) {
            throw new \InvalidArgumentException(
                sprintf('No setter found for property %s in class %s', $property, get_class($entity))
            );
        }

        return $setter;
    }
}
