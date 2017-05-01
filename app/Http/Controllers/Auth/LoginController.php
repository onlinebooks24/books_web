<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{


    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard/home';

    protected function authenticated(Request $request, $user)
    {
     return redirect('/admin/dashboard/home');
    }
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
}
