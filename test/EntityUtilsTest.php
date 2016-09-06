<?php

namespace WebservicesNl\Utils\test;

use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;
use WebservicesNl\Utils\EntityUtils;
use WebservicesNl\Utils\Test\Entities\SomeClass;

/**
 * Class StringUtilsTest.
 */
class EntityUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FactoryMuffin
     */
    protected static $fm;

    /**
     * Setup beforeClass.
     */
    public static function setupBeforeClass()
    {
        // create a new factory muffin instance
        static::$fm = new FactoryMuffin();

        // load your model definitions
        static::$fm->loadFactories(__DIR__ . '/Factories');

        // you can optionally set the faker locale
        Faker::setLocale('nl_NL');
    }

    /**
     * @param array $entity
     *
     * @dataProvider getEntities
     */
    public function testEntityGetter($entity)
    {
        $properties = array_keys($entity->toArray());

        foreach ($properties as $key) {
            $item = EntityUtils::extractValueFromEntity($entity, $key);

            $getter = EntityUtils::createGetter($entity, $key);
            self::assertSame($entity->$getter(), $item, 'trying to access ' . $key);
        }
    }

    /**
     * test class without getter
     */
    public function testEntityWithoutGetters()
    {
        $entity = new SomeClass();

        $boo = EntityUtils::extractValueFromEntity($entity, 'boo');
        $bar = EntityUtils::extractValueFromEntity($entity, 'bar');
        $baz = EntityUtils::extractValueFromEntity($entity, 'baz');

        static::assertEquals($boo, 'boo');
        static::assertEquals($bar, 'bar');
        static::assertEquals($baz, 'baz');

        $nonExistingCallable = 'pie';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'No getter found for property ' . $nonExistingCallable . ' in class ' . get_class($entity)
        );
        EntityUtils::extractValueFromEntity($entity, $nonExistingCallable);
    }

    /**
     * test the setters
     */
    public function testEntitySetter()
    {
        $entity = new SomeClass();
        $properties = ['boo', 'bar', 'baz'];

        foreach ($properties as $key) {
            $value = $key . 'lala';
            EntityUtils::setValueInEntity($entity, $key, $value);
        }
        self::assertEquals('boolala', $entity->boo());
        self::assertEquals('barlala', $entity->bar());
        self::assertEquals('bazlala', $entity->baz());

        $nonExistingCallable = 'pie';
        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage(
            'No setter found for property ' . $nonExistingCallable . ' in class ' . get_class($entity)
        );
        EntityUtils::setValueInEntity($entity, $nonExistingCallable, 'lalala');
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        // create a new factory muffin instance
        $fm = new FactoryMuffin();
        $fm->loadFactories(__DIR__ . '/Factories');

        // you can optionally set the faker locale
        Faker::setLocale('nl_NL');

        return [
            [$fm->instance('WebservicesNl\Utils\Test\Entities\Project')],
            [$fm->instance('WebservicesNl\Utils\Test\Entities\User')],
            [$fm->instance('WebservicesNl\Utils\Test\Entities\Address')],
        ];
    }
}
