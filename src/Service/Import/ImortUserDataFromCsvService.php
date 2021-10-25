<?php

namespace App\Service\Import;

use App\Entity\User;
use App\Service\Import\ImportResult;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Import\SuccessfullImport;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class ImortUserDataFromCsvService 
{
    private $usersCsvFilePath;
    private $em;

    public function __construct(EntityManagerInterface $em, string $projectDir, string $csvFolder, string $userCsvFileName) {
        $this->usersCsvFilePath = $projectDir.DIRECTORY_SEPARATOR.$csvFolder.DIRECTORY_SEPARATOR.$userCsvFileName;
        $this->em = $em;
    }

    public function import(): ImportResult
    {
        # get file content decoded
        $fileContentArray = $this->getDecodedFileContentAsArray();
        # deserialize content
        $serializer = $this->getCsvSerializer();
        $userRepo = $this->em->getRepository(User::class);



        // dd($firstUser);
        $errors = [];
        $successes = [];
        foreach ($fileContentArray as $userData) {
            $csvUser = $serializer->denormalize($userData, User::class);
            # check if user exists
            $user = $userRepo->findOneBy(['email' => $csvUser->getEmail()]);
            if ($user) {
                # maybe update its data...
                continue;
            }

            try {
                $this->saveEntity($csvUser);
                $successes[] = new SuccessfullImport('new user with E-mail: '.$csvUser->getEmail(). ' was successfully created');
            } catch (\Throwable $th) {
                //throw $th;
                $errors[] = (new ErrorImport('failed to save user with E-mail '.$csvUser->getEmail()))->setInfo($th->getMessage());
            }

        }
        return new ImportResult($errors, $successes);
    }

    private function getDecodedFileContentAsArray() : array
    {
        # check file exist
        if (!file_exists($this->usersCsvFilePath)) {
            throw new Exception("users csv import file is missing!", 1);
        }

        /* check file not empty*/
        $fileContent = trim(file_get_contents($this->usersCsvFilePath));
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

    private function saveEntity(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }
    
}
