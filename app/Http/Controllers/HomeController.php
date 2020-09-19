<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Hashing\BcryptHasher;
use App\Models\User;
use App\Models\User_skills;
use App\Models\User_friends;
use Auth;
Use Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   $user_id= Auth::user()->id;
        $data = User_friends::with(['user'])           
        ->where('friend_id', '=', $user_id)
        ->where('status', '=', 'pending')
        ->get();
        return view('home', compact('data'));
    }
}
