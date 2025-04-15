<?php

namespace App\Exceptions;

use Exception;

class PostNotFoundException extends Exception {
    private $id;
    public function __construct($id)
    {
        $this->id = $id;
        parent::__construct("El Post con id {$id} no se ha encontrado.");
    }
}
