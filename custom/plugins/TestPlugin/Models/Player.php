<?php

namespace  TestPlugin\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="test_player", options={"collate"="utf8_unicode_ci"})
 */
class Player extends ModelEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column()
     */
    private $name;

    /**
     * @var string
     * @ORM\Column()
     */
    private $surname;

    /**
     * @var int
     * @ORM\Column()
     */
    private $eta;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Player
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return Player
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return int
     */
    public function getEta()
    {
        return $this->eta;
    }

    /**
     * @param int $eta
     * @return Player
     */
    public function setEta($eta)
    {
        $this->eta = $eta;
        return $this;
    }



    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="Team",inversedBy="players")
     */
    private $idteam;

    /**
     * @return Team
     */
    public function getIdteam()
    {
        return $this->idteam;
    }

    /**
     * @param Team $idteam
     * @return Player
     */
    public function setIdteam($idteam)
    {
        $this->idteam = $idteam;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Player
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}