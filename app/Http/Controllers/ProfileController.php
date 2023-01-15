<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show()
    {
        $data = $this->userService->getUser(JWTAuth::user()->id);
        return ResponseHelper::response_success('User\'s Profile', $data);
    }
}
