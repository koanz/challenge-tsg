<?php

namespace App\Exceptions;

use Exception;

class PostNotFoundException extends Exception {
    private $id;
    protected $message = 'El Usuario con id {$id} no se encontró.';

    public function __construct($id) {
        $this->id = $id;
    }
}
