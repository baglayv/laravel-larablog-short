<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Куда выполнять редирект после входа в систему и после выхода из системы
    */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /* 
        Сразу после входа выполняем редирект и устанавливаем flash-сообщение
    */
    protected function authenticated(Request $request)
    {
        //return redirect($this->redirectTo)->with('status', __('You are logged in!'));
        return redirect($this->redirectTo)->with('success', __('You are logged in!'));
    }

    /**
     * Сразу после выхода выполняем редирект и устанавливаем flash-сообщение
     */ 
    protected function loggedOut(Request $request)
    {
        //return redirect($this->redirectTo)->with('status', trans('auth.loggedout'));
        return redirect($this->redirectTo)->with('success', trans('auth.loggedout'));
    }
}
