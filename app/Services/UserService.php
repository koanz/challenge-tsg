<?php

namespace App\Services;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Exceptions\UserNotFountException;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct() {
    }

    public function create($data): User {
        Log::info('Creating User in UserService');

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => hash::make($data['password']),
        ]);
    }

    public function findByid($id): User {
        Log::info("Find User with id: $id in UserService");

        try {
            return User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Usuario con ID {$id} no encontrado.");

            throw new \Exception("El Usuario con ID {$id} no se ha encontrado.");
        }
    }

    public function update(UserRegisterRequest $data, $id): User {
        Log::info("Update User with id: $id in UserService");

        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Usuario con ID {$id} no encontrado.");

            throw new \Exception("El Usuario con ID {$id} no se ha encontrado.");
        }

        $user->update($data->all());

        return $user;
    }

    public function delete($id): void {
        Log::info("Delete User with id: $id in UserService");

        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Usuario con ID {$id} no encontrado.");

            throw new UserNotFountException($id);
        }

        $user->delete();
    }
}
