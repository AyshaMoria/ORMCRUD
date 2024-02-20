<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    function registration(Request $req)
    {



        $validator = Validator::make($req->all(), [
            'yname' => 'required',
            'contact' => 'required',
            'email' => ['required', 'email', Rule::unique('registrations', 'email')],
            'pass' => 'required|min:6',
            'repass' => 'required|same:pass|min:6',
        ], [
            'yname.required' => 'Name is required.',
            'contact.required' => 'contact Number is required.',
            'email.unique' => 'Email address is already registered.',
            'email.required' => 'email is required.',
            'pass.required' => 'The password is required.',
            'pass.min' => 'The password must be at least 6 characters.',
            'repass.required' => 'The password is required.',
            'repass.same' => 'The confirmation password must match the password.',
            'repass.min' => 'The password must be at least 6 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $reg = new Registration;
        // print_r ($req->input());die;
        $reg->name = $req['yname'];
        $reg->contactno = $req['contact'];
        $reg->email = $req['email'];
        $reg->password = Hash::make($req['pass']);
        $reg->save();
        return redirect('/dashboard');
    }
    function getData()
    {
        $test_data = Registration::orderBy('updated_at', 'DESC')->get();
        

        return view('viewRegistration', [
            'status' => 200,
            'data' => $test_data,
        ]);
    }
    function delete_user($id)
    {
        $data = Registration::find($id);
        if ($data) {
            $data->delete();
            return redirect('/viewReg')->with('success', 'User Successfully Deleted');
        } else {
            return redirect('/viewReg')->with('error', 'Record not found');
        }
    }

    function loginview()
    {
        return view('login');
    }




    function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $rememberMe = $request->has('rememberme');

        if (Auth::attempt($credentials, $rememberMe)) {
            // Authentication was successful
            return redirect('/dashboard');
        } else {
            // Authentication failed
            $user = Registration::where('email', $credentials['email'])->first();

            if (!$user) {
                // Username (email) is incorrect
                return redirect()->back()->withInput()->withErrors(['error' => 'Invalid email']);
            } else {
                // Password is incorrect
                return redirect()->back()->withInput()->withErrors(['error' => 'Invalid password']);
            }
        }
    }



    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the "Remember Me" token
        $request->session()->invalidate();

        // Forget the "Remember Me" token cookie
        $cookie = Cookie::forget('remember_web');

        // Return the response with the cookie forgotten
        return redirect('/')->withCookie($cookie);
    }

    function dashboard()
    {
        return view('dashboard');
    }
}
