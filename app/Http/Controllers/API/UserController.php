<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Carbon\Carbon;

class UserController extends Controller
{

    public function index()
    {
        $users = UserResource::collection(User::orderBy('created_at', 'asc')->get());

        foreach ($users as $user) {
            $carbon = Carbon::create($user->created_at);
           $date = $carbon->toDateTimeString();
           $user->created_at = $date;
        }

        return $users;
    }
}
