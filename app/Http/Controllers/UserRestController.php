<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserRestController extends Controller
{

    public function __construct() {
    }

    public function index(): JsonResponse {
        $users = User::paginate(10);

        return response()->json($users);
    }
}
