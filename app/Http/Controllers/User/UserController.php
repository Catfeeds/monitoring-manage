<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserCode;

class UserController extends Controller
{
    public function invite($code)
    {
        $userCode = UserCode::where('code',$code)->first();

    }
}
