<?php

namespace WebservicesNl\Utils\Test\Entities;

/**
 * Class DummyClass.
 */
class DummyClass
{
    /**
     * @return bool
     */
    public function delete()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function save()
    {
        return true;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
