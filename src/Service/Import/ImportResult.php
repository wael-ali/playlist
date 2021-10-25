<?php

namespace App\Service\Import;


class ImportResult 
{
    private $successes;
    private $errors;

    public function __construct(array $errors, array $successes) {
        $this->errors = $errors;
        $this->successes = $successes;
    }

    public function geterrors() : array
    {
       return $this->errors;
    }
    public function getsuccesses() : array
    {
       return $this->successes;
    }
    
    public function hasErrors() : bool
    {
       return count($this->errors) > 0;
    }
    public function hasSuccesses() : bool
    {
       return count($this->successes) > 0;
    }
    

    
    
}
