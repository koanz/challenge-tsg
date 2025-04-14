<?php

namespace App\Exceptions;

use Exception;

class UserNotFountException extends Exception
{
    private $id;
    public function __construct($id)
    {
        $this->id = $id;
        parent::__construct("El Usuario con id {$id} no se ha encontrado.");
    }
}
