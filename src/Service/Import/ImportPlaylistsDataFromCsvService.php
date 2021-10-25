<?php

namespace App\Service\Import;

use Exception;
use App\Entity\Mp3;
use App\Entity\User;
use App\Entity\Playlist;
use App\Service\Import\Mp3CsvType;
use App\Service\Import\ErrorImport;
use App\Service\Import\ImportResult;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Import\SuccessfullImport;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class ImportPlaylistsDataFromCsvService 
{
    private $playlistsCsvFilePath;
    private $em;

    public function __construct(EntityManagerInterface $em, string $projectDir, string $csvFolder, string $playlistsCsvFileName) {
        $this->playlistsCsvFilePath = $projectDir.DIRECTORY_SEPARATOR.$csvFolder.DIRECTORY_SEPARATOR.$playlistsCsvFileName;
        $this->em = $em;
    }

    public function import(): ImportResult
    {
        # get file content decoded
        $fileContentArray = $this->getDecodedFileContentAsArray();
        # deserialize content
        $serializer = $this->getCsvSerializer();

        $errors = [];
        $successes = [];
        foreach ($fileContentArray as $playlistData) {
            $playlistFactory = $serializer->denormalize($playlistData, PlayListFactory::class);
            $playlist;
            try {
                $playlist = $playlistFactory->getPlayListEntity($this->em);
            } catch (\Throwable $th) {
                //throw $th;
                $errors[] = new ErrorImport($th->getMessage());
                continue;
            }

            try {
                $this->saveEntity($playlist);
                $successes[] = new SuccessfullImport('new playlist with Name: '.$playlist->getName(). ' was successfully created');
            } catch (\Throwable $th) {
                //throw $th;
                $importError = new ErrorImport('failed to create new playlist with Name:  '.$playlist->getName());
                $importError->setInfo($th->getMessage());
                $errors[] = $importError;
            }

        }
        return new ImportResult($errors, $successes);
    }

    private function getDecodedFileContentAsArray() : array
    {
        # check file exist
        if (!file_exists($this->playlistsCsvFilePath)) {
            throw new Exception("users csv import file is missing!", 1);
        }
        # check file not empty
        $fileContent = trim(file_get_contents($this->playlistsCsvFilePath));
        if ($fileContent === "" || $fileContent == false) {
            throw new Exception("users csv import file is empty!!!", 1);
        }
        $serializer = $this->getCsvSerializer();
        return $serializer->decode($fileContent, 'csv');
    }

    private function getCsvSerializer() : Serializer
    {
       return new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    }

    private function saveEntity(Playlist $playlist)
    {
        $this->em->persist($playlist);
        $this->em->flush();
    }
    
}
