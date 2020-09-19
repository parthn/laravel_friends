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

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_no' => ['required', 'string', 'min:10', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8','confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect('register')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();

        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone_no = $request->input('phone_no');
        $user->email = $request->input('email');
        $user->password = (new BcryptHasher())->make($request->input('password'));
        $user->save();

        if (!empty($data['skill_name']) && !empty($user->id)) {
            foreach ($data['skill_name'] as $key => $value) {
                $user_skill = new User_skills();
                $user_skill->skill_name = $value;
                $user_skill->user_id =  $user->id;
                $user_skill->save(); 
            }              
        }
        return redirect('register');
    }


    public function index(Request $request)
    {
        $filter = $request->all();
      
        if (isset($filter))
        {
        //   dd(Auth::user()->id);
            $user_id= Auth::user()->id;
            $data['recordsTotal'] = User::where('id', '!=', Auth::user()->id)->count();
            
            $data['recordsFiltered'] = User::with(['user_skills','friend_requests_nosent'])
                    ->where(function($q) use ($filter){
                        $q->orWhere('first_name', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('phone_no', 'LIKE', '%' . $filter['search']['value'] . '%')                        
                        ->whereHas('user_skills', function ($que) use ($filter){
                            $que->where('skill_name', 'LIKE', '%' . $filter['search']['value'] . '%');
                        });                     
                    })    
                    ->orderBy($filter['order'][0]['column'], $filter['order'][0]['dir'])
                    ->where('id', '!=', Auth::user()->id);
                    if(!empty($filter['search_user'])){
                        $data['recordsFiltered'] = $data['recordsFiltered']->where(function($q) use ($filter){
                            $q->orWhere('first_name', 'LIKE', '%' . $filter['search_user'] . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $filter['search_user'] . '%')
                            ->orWhere('email', 'LIKE', '%' . $filter['search_user'] . '%')
                            ->orWhere('phone_no', 'LIKE', '%' . $filter['search_user'] . '%');                
                        });   
                    }
                    if(!empty($filter['search_user_skill'])){
                        $data['recordsFiltered'] = $data['recordsFiltered']->whereHas('user_skills', function ($que) use ($filter){
                            $que->where('skill_name', 'LIKE', '%' . $filter['search_user_skill'] . '%');
                        });     
                    }
                    $data['recordsFiltered'] = $data['recordsFiltered']->count();

                    $data['data'] = User::skip($filter['start'])
                    ->take($filter['length'])
                    ->with(['user_skills','friend_requests_nosent'])
                    ->where(function($q) use ($filter){
                        $q->orWhere('first_name', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('phone_no', 'LIKE', '%' . $filter['search']['value'] . '%')                        
                        ->whereHas('user_skills', function ($que) use ($filter){
                            $que->where('skill_name', 'LIKE', '%' . $filter['search']['value'] . '%');
                        });                     
                    });        
                    if(!empty($filter['search_user'])){
                        $data['data'] = $data['data']->where(function($q) use ($filter){
                            $q->orWhere('first_name', 'LIKE', '%' . $filter['search_user'] . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $filter['search_user'] . '%')
                            ->orWhere('email', 'LIKE', '%' . $filter['search_user'] . '%')
                            ->orWhere('phone_no', 'LIKE', '%' . $filter['search_user'] . '%');                
                        });   
                    }
                    if(!empty($filter['search_user_skill'])){
                        $data['data'] = $data['data']->whereHas('user_skills', function ($que) use ($filter){
                            $que->where('skill_name', 'LIKE', '%' . $filter['search_user_skill'] . '%');
                        });     
                    }            
                    $data['data'] = $data['data']->orderBy($filter['order'][0]['column'], $filter['order'][0]['dir'])
                    ->where('id', '!=', Auth::user()->id)
                    ->get();
                    
    
            return response()->json($data, 200);
        } else
        {
            return response()->json('Not Found', 200);
        }
    }
 
    public function sendrequest($id)
    {
        if (isset($id))
        {
            $user = User::find($id);
             if(!empty($user)){
                $User_friends = new User_friends();                
                $User_friends->user_id = Auth::user()->id;
                $User_friends->friend_id = $id;
                $User_friends->status = 'pending';
                $User_friends->save(); 
                return response()->json('request send success', 200);
            }
    
            return response()->json('User Not found', 500);
        } else
        {
            return response()->json('Not Found', 500);
        }
    }
  
    public function acceptrequest($id)
    {
        if (isset($id))
        {
            $User_friends = User_friends::find($id);      
             if(!empty($User_friends)){
                     
                $data['status'] = 'confirmed';
                $User_friends->update($data); 
                return response()->json('request send success', 200);
            }
    
            return response()->json('User Not found', 500);
        } else
        {
            return response()->json('Not Found', 500);
        }
    }
   
    public function rejectrequest($id)
    {
        if (isset($id))
        {
            $User_friends = User_friends::find($id);      
             if(!empty($User_friends)){
                     
                $data['status'] = 'blocked';
                $User_friends->update($data); 
                return response()->json('request send success', 200);
            }
    
            return response()->json('User Not found', 500);
        } else
        {
            return response()->json('Not Found', 500);
        }
    }


    public function getUserConnection(Request $request)
    {
        $filter = $request->all();
      
        if (!empty($filter))
        { $user_id= Auth::user()->id;
            $data['recordsTotal'] = User::where('id', '!=', Auth::user()->id)->count();
            
            $data['recordsFiltered'] = User::with(['user_skills','friend_requests_confirmed'])
                        ->where(function($q) use ($filter){
                            $q->orWhere('first_name', 'LIKE', '%' . $filter['search']['value'] . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $filter['search']['value'] . '%')
                            ->orWhere('email', 'LIKE', '%' . $filter['search']['value'] . '%')
                            ->orWhere('phone_no', 'LIKE', '%' . $filter['search']['value'] . '%')                        
                            ->whereHas('user_skills', function ($que) use ($filter){
                                $que->where('skill_name', 'LIKE', '%' . $filter['search']['value'] . '%');
                            });                     
                        }) 
                     ->whereHas('friend_requests_confirmed', function ($que) use ($user_id){
                        $que->where('user_id', $user_id);
                     })   
                    ->orderBy($filter['order'][0]['column'], $filter['order'][0]['dir'])
                    ->where('id', '!=', Auth::user()->id)
                    ->count();
            $data['data'] = User::skip($filter['start'])
                    ->take($filter['length'])
                    ->with(['user_skills','friend_requests_confirmed'])
                    ->where(function($q) use ($filter){
                        $q->orWhere('first_name', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $filter['search']['value'] . '%')
                        ->orWhere('phone_no', 'LIKE', '%' . $filter['search']['value'] . '%')                        
                        ->whereHas('user_skills', function ($que) use ($filter){
                            $que->where('skill_name', 'LIKE', '%' . $filter['search']['value'] . '%');
                        });                     
                    })   
                    ->whereHas('friend_requests_confirmed', function ($que) use ($user_id){
                        $que->where('user_id', $user_id);
                     })                 
                    ->orderBy($filter['order'][0]['column'], $filter['order'][0]['dir'])
                    ->where('id', '!=', Auth::user()->id)
                    ->get()->toArray();
    
            return response()->json($data, 200);
        }

        return view('connection');
    }

}
