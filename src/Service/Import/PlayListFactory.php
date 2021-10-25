<?php

namespace App\Service\Import;

use App\Entity\Mp3;
use App\Entity\User;
use App\Entity\Playlist;
use App\Repository\Mp3Repository;
use App\Repository\UserRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;


class PlayListFactory
{

    private $id;
    private $name;
    private $user_id;
    private $mp3_ids;

    
    public function getId(): string
    {
        return  $this->id;
    }
    public function setId(string $id)
    {
        return $this->id = $id;
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

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getMp3Ids(): ?string
    {
        return $this->mp3_ids;
    }
    public function getMp3IdsAsArray(): array
    {
        $mp3IdsArray = [];
        if (trim($this->mp3_ids) !== "") {
            $mp3IdsArray = explode(",", $this->mp3_ids);
        }
        return $mp3IdsArray;
    }

    public function setMp3Ids(string $mp3_ids): self
    {
        $this->mp3_ids = $mp3_ids;

        return $this;
    }

    public function getPlayListEntity(EntityManagerInterface $em) : Playlist
    {
        $playlistRepo = $em->getRepository(Playlist::class);
        $this->isPlaylistAlreadyCreated($playlistRepo);
        $usersRepo = $em->getRepository(User::class);
        $user = $this->getUserRelation($usersRepo);
        $mp3sRepo = $em->getRepository(Mp3::class);
        $mp3s = $this->getMp3sRelatedToRelation($mp3sRepo);

        return (new Playlist())
            ->setName($this->getName())
            ->setUser($user)
            ->addMp3sArray($mp3s)
        ;
    }

    // Privates

    private function getUserRelation(UserRepository $repo) : User
    {
        $userId = $this->getUserId();
        $user =  $repo->findOneById($userId);
        if ($user) {
            return $user;
        }
        throw new \Exception("can't create playlist with name (".$this->getName()."). User with ID==(".$userId.") Not Found", 1); 
    }

    private function getMp3sRelatedToRelation(Mp3Repository $repo)
    {
        $mp3_ids = $this->getMp3IdsAsArray(); 
        if (count($mp3_ids) === 0) {
            return [];
        }
        $mp3s =  $repo->findBy(['id' => $mp3_ids]);
        return $mp3s;
    }
    private function isPlaylistAlreadyCreated(PlaylistRepository $repo) : bool
    {
        $id = $this->getId();
        $playlist =  $repo->findOneById($id);
        // dd($playlist);
        if ($playlist) {
            throw new \Exception("playlist with name (".$this->getName()."). is already Created", 1); 
        }
        return true;

    }
}
