<?php

namespace WebservicesNl\Utils\Test\Entities;

/**
 * Class Project.
 */
class Project extends DummyClass
{
    /**
     * @var Address
     */
    protected $address;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $website;

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'address' => ($this->address === null) ? null : $this->address->toArray(),
            'dateCreated' => $this->dateCreated->format('c'),
            'description' => $this->description,
            'name' => $this->name,
            'user' => ($this->user === null) ? null : $this->user->toArray(),
            'website' => $this->website
        ];
    }
}
