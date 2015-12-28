<?php

namespace CodeDelivery\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use CodeDelivery\Repositories\UserRepository;

class OAuthCheckRole
{

    private $userRepository;

    public function __contruct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  IlluminateHttpRequest  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role) //aqui adicionamos um parametro para o middleware
    {

        $id = Authorizer::getResourceOwnerId();
        $user = $this->userRepository->find($id);
        if ($user->role != $role)
        {
            abort(403, 'Access Forbiden');
        }
        return $next($request);
    }

}
