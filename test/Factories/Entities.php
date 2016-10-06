<?php

use League\FactoryMuffin\Faker\Facade as Faker;

/** @var League\FactoryMuffin\FactoryMuffin $fm */
$fm->define(\WebservicesNl\Utils\Test\Entities\Geocode::class)->setDefinitions(
    [
        'longitude' => Faker::longitude(),
        'latitude' => Faker::latitude(),
    ]
);

$fm->define(\WebservicesNl\Utils\Test\Entities\Address::class)->setDefinitions(
    [
        'city' => Faker::city(),
        'country' => Faker::country(),
        'geoCode' => function () use ($fm) {
            return $fm->instance('WebservicesNl\Utils\Test\Entities\Geocode');
        },
        'houseNumber' => Faker::buildingNumber(),
        'postcode' => Faker::postcode(),
        'streetName' => Faker::streetName(),
        'streetSuffix' => Faker::streetSuffix(),
        'valid' => Faker::boolean(),
    ]
);

$fm->define(\WebservicesNl\Utils\Test\Entities\User::class)->setDefinitions(
    [
        'address' => function () use ($fm) {
            return $fm->instance('WebservicesNl\Utils\Test\Entities\Address');
        },
        'email' => Faker::email(),
        'firstName' => Faker::firstName(),
        'lastName' => Faker::lastName(),
        'title' => Faker::title(),
    ]
);

$fm->define(\WebservicesNl\Utils\Test\Entities\Project::class)->setDefinitions(
    [
        'address' => function () use ($fm) {
            return $fm->instance('WebservicesNl\Utils\Test\Entities\Address');
        },
        'dateCreated' => Faker::dateTimeBetween('-1year', '-1month'),
        'description' => Faker::sentence(),
        'name' => Faker::name(),
        'user' => function () use ($fm) {
            return $fm->instance('WebservicesNl\Utils\Test\Entities\User');
        },
        'website' => Faker::url()
    ]
);
