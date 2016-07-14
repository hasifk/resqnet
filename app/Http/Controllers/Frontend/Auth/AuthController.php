<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Repositories\Frontend\Access\User\EloquentUserRepository;
use App\Services\Access\Traits\ConfirmUsers;
use App\Services\Access\Traits\UseSocialite;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Services\Access\Traits\AuthenticatesAndRegistersUsers;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend\Auth
 */
class AuthController extends Controller
{

    protected $users;

    public function __construct( EloquentUserRepository $users)
    {
        $this->users = $users;
    }
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    /*protected $redirectTo = '/dashboard';*/

    public function register(RegisterRequest $request)
    {
        $user = $this->users->create($request->all());
        $token = \JWTAuth::fromUser($user);
        return response()->json(['token' => $token, 'user' => $user->toArray()]);
    }



}