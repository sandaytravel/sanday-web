<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use App\User;
use App\EmailTemplate;
use App\Mysqlusers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller {
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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        Auth::viaRemember();
        Auth::check();
        Auth::viaRemember();
        $this->middleware('guest')->except('logout');
    }

    // Show admin login form
    public function showLoginForm() {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        } else {
            return view('auth.login');
        }
    }

    // Authenticating admin
    public function postLogin(Request $request) {
        if (!empty($request->email) && !empty($request->password)) {
            $user = User::Where('email', '=', $request->email)->whereIn('role_id', [1   ,3])->where('is_delete',0)->first();
            if (!empty($user)) {
                if ($user->status == "Active") {
                    if (\Hash::check($request->password, $user->password)) {
                        Auth::loginUsingId($user->id);
                        $request->session()->regenerate();
                        if ($user->role_id == "1") {
                            return redirect()->route('dashboard');
                        }else{
                            return redirect('merchant/dashboard');
                        }
                    }else {
                        Session::flash('error', 'Password is invalid.');
                        return redirect()->route('login');
                    }
                } else {
                    Session::flash('error', 'Your account has been deactivated. Please contact San travel team.');
                    return redirect()->route('login');
                }
            } else {
                Session::flash('error', 'No such registered user found. Please try again.');
                return redirect()->route('login');
            }
        } else {
            Session::flash('error', 'Please provide email and password.');
            return redirect()->route('login');
        }
    }

    // Forgot password
    public function postForgotPassword(Request $request) {
        if (isset($request->email)) {
            $user = User::Where('email', '=', $request->email)->first();
            if ($user) {
                $dataArray = ["id" => $user->id, "name" => $user->name, "email" => $user->email, 'reseturl' => url('/resetpassword/' . $user->id)];

                $template = EmailTemplate::where('name', 'forgot-password')->first();
                Mail::send([], [], function($message) use ($template,$dataArray) {
                    $data = [
                        'toUserName' => $dataArray['name'],
                        'resetLink' => $dataArray['reseturl'],
                    ];

                    $message->to($dataArray['email'], $dataArray['name'])
                            ->subject($template->subject)
                            ->setBody($template->parse($data), 'text/html');
                });
                DB::table('users')
                        ->where('id', "=", $user->id)
                        ->update(['reset_token' => date('Y-m-d h:i:s', time()),
                            'updated_at' => date('Y-m-d h:i:s', time())]);

                Session::flash('success', 'Reset password email sent to your registered account.');
                return redirect()->route('loginform');
            } else {
                Session::flash('error', 'No such registered user found.');
                return redirect()->back();
            }
        } else { // Enter valid username and password
            Session::flash('error', 'Please enter valid email address.');
            return redirect()->back();
        }
    }

    // Show reset password form
    public function showResetPasswordForm(Request $request) {
        if (is_numeric($request->id)) {
            if ($request->id != null) {
                $token = DB::table('users')
                        ->where('id', "=", $request->id)
                        ->first();
                if($token != null){
                    if ( $token->reset_token != null) {
                        $diff = strtotime(date("Y-m-d h:i:s")) - strtotime($token->reset_token);
                        $minuteDifference = round(abs($diff) / 60, 2);
                        if ($minuteDifference > 30) { // Check if token is expires or not. Token will be expire after 30 minutes
                            Session::flash('error', 'Token has been expired.');
                            return redirect()->route('loginform');
                        } else { // Show reset password form
                            return view('auth.resetpassword');
                        }
                    } else {
                        return view('errors.404');
                    }
                }else{
                    return view('errors.404');
                }
            } else {
                Session::flash('success', 'Something went wrong. Please try again.');
                return redirect()->route('loginform');
            }
        } else {
            return view('errors.404');
        }
    }

    // Post forgot password form
    public function postResetPassword(Request $request) {
       
        if ($request->id != null) {
            DB::table('users')
                    ->where('id', "=", $request->id)
                    ->update(['password' => \Hash::make($request->newpassword),
                        'reset_token' => date('Y-m-d h:i:s', time()),
                        'updated_at' => date('Y-m-d h:i:s', time())]);
            Session::flash('success', 'Your password has been reset successfully.');
            return redirect()->route('loginform');
        } else {
            Session::flash('error', 'Your account does not exists to reset password.');
            return redirect()->route('loginform');
        }
    }

    // Logged out from the system
    public function logout(Request $request) {
        $request->session()->forget('logintime');
        Auth::logout();
        Session::flash('success', 'You are successfully logged out.');
        return redirect()->route('loginform');
    }

    
    // Show Merchant login form
    public function showMerchantLoginForm() {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        } else {
            return view('auth.merchantlogin');
        }
    }

    // Authenticating Mercant Login
    public function merchantPostLogin(Request $request) {
        if (!empty($request->email) && !empty($request->password)) {
            $user = User::Where('email', '=', $request->email)->where('role_id', 3)->where('is_delete',0)->first();
            if (!empty($user)) {
                if ($user->status == "Active") {
                    if (\Hash::check($request->password, $user->password)) {
                        Auth::loginUsingId($user->id);
                        $request->session()->regenerate();
                        return redirect('merchant/dashboard');
                    } else {
                        Session::flash('error', 'Password is invalid.');
                        return redirect()->route('merchantloginform');
                    }
                } else {
                    Session::flash('error', 'Your account has been deactivated. Please contact San travel team.');
                    return redirect()->route('merchantloginform');
                }
            } else {
                Session::flash('error', 'No such registered user found. Please try again.');
                return redirect()->route('merchantloginform');
            }
        } else {
            Session::flash('error', 'Please provide email and password.');
            return redirect()->route('merchantloginform');
        }
    }

    // Merchant forgot password 
    public function postMerchantForgotPassword(Request $request) {
        if (isset($request->email)) {
            $user = User::Where('email', '=', $request->email)->where('role_id','3')->first();
            if ($user) {
                $dataArray = ["id" => $user->id, "name" => $user->name, "email" => $user->email, 'reseturl' => url('/merchant/resetpassword/' . $user->id)];
                $template = EmailTemplate::where('name', 'forgot-password')->first();
                Mail::send([], [], function($message) use ($template,$dataArray) {
                    $data = [
                        'toUserName' => $dataArray['name'],
                        'resetLink' => $dataArray['reseturl'],
                    ];
                    $message->to($dataArray['email'], $dataArray['name'])
                            ->subject($template->subject)
                            ->setBody($template->parse($data), 'text/html');
                });
                DB::table('users')
                        ->where('id', "=", $user->id)
                        ->update(['reset_token' => date('Y-m-d h:i:s', time()),
                            'updated_at' => date('Y-m-d h:i:s', time())]);

                Session::flash('success', 'Reset password email sent to your registered account.');
                return redirect()->route('merchantloginform');
            } else {
                Session::flash('error', 'No such registered merchant found.');
                return redirect()->back();
            }
        } else { // Enter valid username and password
            Session::flash('error', 'Please enter valid email address.');
            return redirect()->back();
        }
    }
     // Show reset merchant password form
    public function mercahntResetPasswordForm(Request $request) {
        if (is_numeric($request->id)) {
            if ($request->id != null) {
                $token = DB::table('users')
                        ->where('id', "=", $request->id)
                        ->first();
                if ($token != null || $token->reset_token != null) {
                    $diff = strtotime(date("Y-m-d h:i:s")) - strtotime($token->reset_token);
                    $minuteDifference = round(abs($diff) / 60, 2);
                    if ($minuteDifference > 30) { // Check if token is expires or not. Token will be expire after 30 minutes
                        Session::flash('error', 'Token has been expired.');
                        return redirect()->route('loginform');
                    } else { // Show reset password form
                        return view('auth.merchantresetpassword');
                    }
                } else {
                    return view('errors.404');
                }
            } else {
                Session::flash('success', 'Something went wrong. Please try again.');
                return redirect()->route('merchantloginform');
            }
        } else {
            return view('errors.404');
        }
    }
    /*--------Merchant Pssword ----------*/
    public function merchantpostResetPassword(Request $request) {
        if ($request->id != null) {
            DB::table('users')
                    ->where('id', "=", $request->id)
                    ->update(['password' => \Hash::make($request->newpassword),
                        'reset_token' => date('Y-m-d h:i:s', time()),
                        'updated_at' => date('Y-m-d h:i:s', time())]);
            Session::flash('success', 'Your password has been reset successfully.');
            return redirect()->route('merchantloginform');
        } else {
            Session::flash('error', 'Your account does not exists to reset password.');
            return redirect()->route('merchantloginform');
        }
    }
    /*----------Merchant Logout-------------*/
    public function merchantLogout(Request $request) {
        $request->session()->forget('logintime');
        Auth::logout();
        Session::flash('success', 'You are successfully logged out.');
        return redirect()->route('merchantloginform');
    }


    /**
     * test curl workig or not #region
     */
    public function curlTest(Request $request) {
            // Get cURL resource
            phpinfo();
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            //CURLOPT_URL => 'http://testcURL.com/?item1=value&item2=value2',
            //CURLOPT_USERAGENT => 'Codular Sample cURL Request'
            ));
            // Send the request & save response to $resp
            $resp = curl_exec($curl);
            // Close request to clear up some resources
            curl_close($curl);
           print_r($resp);
        
    }
}
