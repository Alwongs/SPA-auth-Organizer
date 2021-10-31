<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        if (Auth::id() == 4) {
            $users = UserResource::collection(User::orderBy('created_at', 'asc')->get());
            foreach ($users as $user) {
                $carbon = Carbon::create($user->created_at);
               $date = $carbon->toDateTimeString();
               $user->created_at = $date;
            }
        } else {
            $users = [];
        }

        return $users;
    }
}
