<?php

namespace App\Http\Controllers;

use App\Mail\NewUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function register(Request $request){
        $data = $request->all();
        $loginData = $request->only(['username', 'password']);
        $credentials = $request->validate([
            // 'email' => 'required|email:rfc,dns|unique:users,email',
            // 'username' => 'required|unique:users,username',
            // 'password' => 'required|min:8',
            // 'password_confirmation' => 'required|same:password'
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);
        print_r($credentials);
        die;
        $user = User::create($credentials);
        // Auth::login($user);
        $this->authenticate($request);
        Mail::send(new NewUserMail($user));
    }


    public function authenticate(Request $request)
    {
        $data = $request->all();
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]); 

        if (Auth::attempt($credentials)) {
            if (Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
                if(User::find($data) != null){
                    $user = User::find($data);
                    $affected = User::updateUserStatus($user->id);
                    if(Hash::check($data['password'], $user->password)){
                        // echo $user->role;
                        $request->session()->regenerate();
                        session(['user.role' => $user->role]);
                        if($user->role == "Admin"){
                            return redirect()->intended(route('user.dashboard', Auth::user()->username));
                        } else {
                            return redirect()->intended('/');
                        }
                    }
                }
            }
        }
        else {
            $error = "User does not exist!";
            return back()->withErrors([
                'auth_error' => $error,
            ]);
        }
    }
    
    /**
     * unauthenticated
     * 
     * @return void
     */
    public function unauthenticated(){
        Auth::logout();
        session()->flush();
        return redirect('/login');
    }
}
