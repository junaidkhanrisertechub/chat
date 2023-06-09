<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:user-list|user-create', ['only' => ['index','create','store']]);
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        // Get user with emplyees role
        $users = User::role('employee')->get();
        return view('users.index', compact('users'));
    }

    // Create user view
    public function create()
    {        
        return view('users.create');
    }
    
    // Save user info
    public function store(Request $request)
    {
        $this->validate($request, [ 
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:10', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $data= $request->all();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        $user->assignRole('employee');

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }
}
