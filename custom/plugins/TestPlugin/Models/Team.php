<?php

namespace  TestPlugin\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="test_team", options={"collate"="utf8_unicode_ci"})
 */
class Team extends ModelEntity
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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Player",mappedBy="idteam")
     */
  private $players;

    /**
     * @var string
     * @ORM\Column()
     */
  private $logo;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     * @return Team
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param ArrayCollection $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
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
     * @return Team
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }
}