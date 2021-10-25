<?php

namespace App\Entity;

use App\Entity\Mp3;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 */
class Playlist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="playlists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Mp3::class, inversedBy="playlists")
     */
    private $mp3s;

    public function __construct()
    {
        $this->mp3s = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Mp3[]
     */
    public function getMp3s(): Collection
    {
        return $this->mp3s;
    }

    public function addMp3(Mp3 $mp3): self
    {
        if (!$this->mp3s->contains($mp3)) {
            $this->mp3s[] = $mp3;
            $mp3->addPlaylist($this);
        }

        return $this;
    }
    public function addMp3sArray(array $mp3s): self
    {
        if (count($mp3s) > 0) {
            foreach ($mp3s as $mp3) {
                $this->addMp3($mp3);
            }
        }

        return $this;
    }

    public function removeMp3(Mp3 $mp3): self
    {
        $this->mp3s->removeElement($mp3);

        return $this;
    }
}
