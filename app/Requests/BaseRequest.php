<?php 

class BaseRequest 
{
    protected $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function countErrors()
    {
        return count($this->errors);
    }
}
