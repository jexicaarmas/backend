<?php

namespace App\Http\Controllers;

use Auth; 
use DB; 
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
  private $nicenames = [
      'password'  => 'contraseña', 
      'email'     => 'email'
    ]; 

  public function signIn(Request $request)
  { 
    $this->validate($request, [
        'email'    => 'required|email',  
        'password' => 'required|string|min:6'
    ], [], $this->nicenames);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      $user = User::where('email', $request->email)->first();
      DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked' => 1]);
      $user->setAttribute('access_token', $user->createToken(User::class)->accessToken);
      return $this->showOne($user);
    }
    
    return $this->showMessage(__('auth.existing'), 409); 
  }

  public function signOut(Request $signOut)
  { 
    $user = Auth::user()->token();
    $user->revoke();
    return $this->showMessage(__('auth.logout'));
  }

}
