<?php

namespace App\Service\Import;

use Exception;
use App\Entity\Mp3;
use App\Entity\User;
use App\Service\Import\Mp3CsvType;
use App\Service\Import\ErrorImport;
use App\Service\Import\ImportResult;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Import\SuccessfullImport;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class ImportMp3DataFromCsvService 
{
    private $mp3CsvFilePath;
    private $em;

    public function __construct(EntityManagerInterface $em, string $projectDir, string $csvFolder, string $mp3CsvFileName) {
        $this->mp3CsvFilePath = $projectDir.DIRECTORY_SEPARATOR.$csvFolder.DIRECTORY_SEPARATOR.$mp3CsvFileName;
        $this->em = $em;
    }

    public function import(): ImportResult
    {
        # get file content decoded
        $fileContentArray = $this->getDecodedFileContentAsArray();
        # deserialize content
        $serializer = $this->getCsvSerializer();
        $mp3Repo = $this->em->getRepository(Mp3::class);



        $errors = [];
        $successes = [];
        // $temp = [];
        foreach ($fileContentArray as $userData) {
            $mp3CsvType = $serializer->denormalize($userData, Mp3CsvType::class);
            # check if user exists
            $mp3 = $mp3Repo->findById($mp3CsvType->getId());
            if ($mp3) {
                # maybe update its data...
                continue;
            }
            try {
                $this->saveEntity($mp3CsvType->getMp3Entity());
                $successes[] = new SuccessfullImport('new mp3 with Title: '.$mp3CsvType->getTitle(). ' was successfully created');
            } catch (\Throwable $th) {
                //throw $th;
                $errors[] = new ErrorImport('failed to save new mp3 with Title:  '.$mp3CsvType->getTitle());
            }

        }
        return new ImportResult($errors, $successes);
    }

    private function getDecodedFileContentAsArray() : array
    {
        # check file exist
        if (!file_exists($this->mp3CsvFilePath)) {
            throw new Exception("users csv import file is missing!", 1);
        }
        # check file not empty
        $fileContent = trim(file_get_contents($this->mp3CsvFilePath));
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

    private function saveEntity(Mp3 $mp3)
    {
        $this->em->persist($mp3);
        $this->em->flush();
    }
    
}
