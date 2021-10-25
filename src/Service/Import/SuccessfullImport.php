<?php

namespace App\Service\Import;


class SuccessfullImport 
{
    private $info;
    private $message;

    public function __construct(string $message = "import is successfull") {
        $this->message = $message;
        $this->info = 'No more info available';
    }

    public function getMessage() : string
    {
       return $this->message;
    }
    public function getInfo() : string
    {
       return $this->info;
    }

    
    
}
