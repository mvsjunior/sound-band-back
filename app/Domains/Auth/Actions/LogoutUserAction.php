<?php 

namespace App\Domains\Auth\Actions;

use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutUserAction {

    public function handle(){
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}