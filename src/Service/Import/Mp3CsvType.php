<?php

namespace App\Service\Import;

use App\Entity\Mp3;


class Mp3CsvType
{

    private $ID;
    private $Title;
    private $Interpret;
    private $Album;
    private $track;
    private $year;
    private $genre;

    public function getId(): ?int
    {
        return  (int)$this->ID;
    }
    public function setId(string $id)
    {
        return $this->ID = $id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $title): self
    {
        $this->Title = $title;

        return $this;
    }

    public function getInterpret(): ?string
    {
        return $this->Interpret;
    }

    public function setInterpret(string $interpret): self
    {
        $this->Interpret = $interpret;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(string $album): self
    {
        $this->Album = $album;

        return $this;
    }

    public function getTrack(): ?string
    {
        return $this->track;
    }

    public function setTrack(string $track): self
    {
        $this->track = $track;

        return $this;
    }

    public function getYear(): ?int
    {
        return (int)$this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getMp3Entity() : Mp3
    {
       return (new Mp3())
            // ->setId($this->ID)
            ->setTitle($this->Title)
            ->setInterpret($this->Interpret)
            ->setAlbum($this->Album)
            ->setYear($this->getYear())
            ->setTrack($this->track)
            ->setGenre($this->genre)
       ;
    }
}
