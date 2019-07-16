<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="team", uniqueConstraints={@ORM\UniqueConstraint(columns={"name"})})
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="strip", type="string", nullable=false)
     */
    private $strip;
    /**
     * @var League[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\League", inversedBy="teams", cascade={"persist"})
     * @ORM\JoinTable(name="league_team",
     *      joinColumns={@ORM\JoinColumn(name="team_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="league_id", referencedColumnName="id")}
     * )
     */
    private $leagues;

    public function __construct()
    {
        $this->leagues = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStrip(): string
    {
        return $this->strip;
    }

    public function setStrip(string $strip): self
    {
        $this->strip = $strip;

        return $this;
    }

    public function getLeagues(): Collection
    {
        return $this->leagues;
    }

    public function addLeague(League $league): self
    {
        $this->leagues->add($league);

        return $this;
    }

    public function removeLeague(League $league): self
    {
        $this->leagues->removeElement($league);

        return $this;
    }

    public function setLeagues(array $leagues): self
    {
        $this->leagues = new ArrayCollection($leagues);

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

}