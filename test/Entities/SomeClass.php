<?php

namespace WebservicesNl\Utils\test\Entities;

/**
 * Class SomeClass.
 */
class SomeClass
{
    /**
     * @var string
     */
    public $boo = 'boo';

    /**
     * @var string
     */
    protected $bar = 'bar';

    /**
     * @var string
     */
    private $baz = 'baz';

    /**
     * @return string
     */
    public function boo()
    {
        return $this->boo;
    }

    /**
     * @return string
     */
    public function bar()
    {
        return $this->bar;
    }

    /**
     * @return string
     */
    public function baz()
    {
        return $this->baz;
    }

    /**
     * @param string $boo
     */
    public function setBoo($boo)
    {
        $this->boo = $boo;
    }

    /**
     * @param string $bar
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    /**
     * @param string $baz
     */
    public function setBaz($baz)
    {
        $this->baz = $baz;
    }
}
