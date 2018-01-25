<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="test_car")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"renault" = "Renault", "citroen" = "Citroen", "peugeot" = "Peugeot"})
 */
abstract class Car
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var Engine
     * @ORM\ManyToOne(targetEntity="Engine", cascade={"persist"}, fetch="EAGER")
     */
    protected $engine;

    /**
     * @var Engine
     * @ORM\ManyToOne(targetEntity="Engine", cascade={"persist"}, fetch="EAGER")
     */
    protected $rescueEngine;

    /**
     * @var Inspection[]|Collection
     * @ORM\OneToMany(targetEntity="Inspection", cascade={"persist", "remove"}, mappedBy="car")
     * @Assert\Valid
     */
    protected $inspections;

    /**
     * @var Color
     * @ORM\ManyToOne(targetEntity="Color", cascade={"persist"}, fetch="EAGER")
     */
    protected $color;

    public function __construct()
    {
        $this->inspections = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setEngine(Engine $engine)
    {
        $this->engine = $engine;
        return $this;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRescueEngine(Engine $rescueEngine)
    {
        $this->rescueEngine = $rescueEngine;
        return $this;
    }

    public function getRescueEngine()
    {
        return $this->rescueEngine;
    }

    public function setInspections($inspections)
    {
        $this->inspections->clear();

        foreach ($inspections as $inspection) {
            $this->addInspection($inspection);
        }
        return $this;
    }

    public function getInspections()
    {
        return $this->inspections;
    }

    public function addInspection(Inspection $inspection)
    {
        $inspection->setCar($this);
        $this->inspections->add($inspection);
        return $this;
    }

    public function removeInspection(Inspection $inspection)
    {
        $this->inspections->removeElement($inspection);
        return $this;
    }

    public function __toString()
    {
        return $this->getName() ?: 'n/a';
    }

     public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }
}