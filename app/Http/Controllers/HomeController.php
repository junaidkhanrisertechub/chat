<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    {
        $role = Auth::user()->roles->pluck("name")->first();
        switch ($role) {
            case 'admin':
                $redirect =  '/admin/dashboard';
                break;
            case 'employee':
                $redirect ='/user/dashboard';
                break;       
            default:
            $redirect ='/login'; 
            break;
        }
        return redirect($redirect);

    }
}
