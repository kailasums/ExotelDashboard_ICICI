<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {
        $accept = $request->has('acceptId')?$request->get('acceptId'):'';
        return view('auth.login',compact('accept'));
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAdminLoginForm(Request $request)
    {
        $accept = $request->has('acceptId')?$request->get('acceptId'):'';
        return view('auth.admin-login',compact('accept'));
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
       
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $path =  $request->path();
            $user = Auth::user();
            Session::put('user', $user);
            $restrictLogin = explode(",",env("NO_PORTAL_ACCESS"));
            
            if(in_array($user->designation,$restrictLogin)) {
                $request->request->add(['level_login_failed' => true]);
                $this->logout($request);  

            } else if(!($user->is_admin === 'YES') && $path === 'login') {
                return $this->sendLoginResponse($request);
            } else if(($user->is_admin === 'YES') && $path === 'login/admin') {
                return $this->sendLoginResponse($request);
            } else if(!(($user->is_admin === 'YES')) && $path === 'login') {
                return $this->sendLoginResponse($request);
            } else {
                $request->request->add(['role_login_failed' => true]);
                $this->logout($request);  
            }   
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
     /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
       
        $this->clearLoginAttempts($request);
        
        if($request->acceptId) {
            return redirect()->intended('/accept/'.$request->acceptId);
        }
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }


    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }
        $user = Auth::user();

        if((($user->is_admin === 'YES'))) {
            return property_exists($this, 'redirectTo') ? '/admin/register-user' : '/admin/register-user';
        } else {
            return property_exists($this, 'redirectTo') ?  $this->redirectTo : '/home';
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
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
        $user = Auth::user();
        $this->guard()->logout();
        $request->session()->invalidate();
        if($user->is_admin) {
            return $this->loggedOut($request) ?redirect('/admin/login'): redirect('/admin/login');
        } 
        return $this->loggedOut($request) ?redirect('/login'): redirect('/login');
    }
    

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $data = $request->all();
        if(isset($data['role_login_failed'])) {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.roleFailed')],
            ]);
        }else if(isset($data['level_login_failed'])){
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.levelFailed')],
            ]);
        } else {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
    }
}
