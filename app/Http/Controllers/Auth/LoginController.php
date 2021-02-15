<?php

namespace App\Http\Controllers\Auth;

use Pusher\Pusher;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    public function redirectTo()
    {
        switch (auth()->user()->role) {
            case 1:
                $this->redirectTo = '/admin/dashboard';
                $this->login_status(1);
                return $this->redirectTo;
                break;
            case 2:
                $this->redirectTo = '/acceptor/dashboard';
                $this->login_status(1);
                return $this->redirectTo;
                break;
            case 3:
                $this->redirectTo = '/operator/dashboard';
                $this->login_status(1);
                return $this->redirectTo;
                break;
            case 4:
                $this->redirectTo = '/customer/dashboard';
                $this->login_status(1);
                return $this->redirectTo;
                break;
            
            default:
                $this->redirectTo = '/login';
                return $this->redirectTo;
        }
    }

    // Pusher login status
    public function login_status($status)
    {
        $user = Auth::user();
        $user->update(['login_status' => $status]);

        $options = [
            'cluster' => 'ap2',
            'useTLS' => true
        ];
        
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data = [
            'reciever_id' => Auth::id(),
            'online' => $status
        ];

        $pusher->trigger('my-channel', 'chat_status', $data);

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Login
    public function showLoginForm(){
      $pageConfigs = [
          'bodyClass' => "bg-full-screen-image",
          'blankPage' => true
      ];

      return view('/auth/login', [
          'pageConfigs' => $pageConfigs
      ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if(isset(Auth::user()->id)){
            $user = Auth::user();
            $user->update(['login_status' => 0]);
            $this->login_status(0);
        }
        

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }
}
