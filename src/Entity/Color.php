<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="test_color")
 */
class Color
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $r;

    /**
     * @ORM\Column(type="string")
     */
    protected $g;

    /**
     * @ORM\Column(type="string")
     */
    protected $b;

    /**
     * @var Material
     * @ORM\ManyToOne(targetEntity="Material", cascade={"persist"}, fetch="EAGER")
     */
    protected $material;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }


    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setB($b)
    {
        $this->b = $b;
        return $this;
    }

    public function getB()
    {
        return $this->b;
    }

    public function setMaterial(Material $material)
    {
        $this->material = $material;
        return $this;
    }

    public function getMaterial(): Material
    {
        return $this->material;
    }

    public function setG($g)
    {
        $this->g = $g;
        return $this;
    }

    public function getG()
    {
        return $this->g;
    }

    public function setR($r)
    {
        $this->r = $r;
        return $this;
    }

    public function getR()
    {
        return $this->r;
    }


    public function toHex() {
        return sprintf("#%02x%02x%02x", $this->getR(), $this->getG(), $this->getB());
    }

    public function label() {
        return sprintf('%s - %s', $this->toHex(), $this->getMaterial()->getName());
    }

}