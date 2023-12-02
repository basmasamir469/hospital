<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\TokenRequest;
use App\Mail\PasswordReseted;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data     = $request->validated();
        $password = Hash::make($data['password']);
        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => $password,
            'mobile'         => $data['mobile'],
        ]);
        $user->addMedia($data['image'])
             ->toMediaCollection('users-images');
        $header_role = $request->header('X-Role','patient');
        $role        = Role::where('name',$header_role)->first();
        $user->assignRole($role);
        return $this->dataResponse(null, __('registered successfuly'),200);

    }

    public function login(LoginRequest $request)
    {
       $data = $request->validated();
       if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']]))
       {
          $user = $request->user();
          $token = $user->createToken('HOSPITAL')->plainTextToken;
          return $this->dataResponse(['token'=>$token], __('logged in successfully'),200);    
       }
       return $this->dataResponse(null, __('failed to login! email or password doesnot meet our credentials'),422);    
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email',$data['email'])->first();
        if($user)
        {
            $code = rand(11111,99999);
            DB::table('password_reset_tokens')->updateOrInsert(['email'=>$data['email']],['token'=>$code,'created_at'=>Carbon::now()]);
            Mail::to($user->email)->send(new PasswordReseted($code));
            return $this->dataResponse(null,__('reset password code is sent successfully please check your email'),200);
        }
        return $this->dataResponse(null,__('email not found!'),422);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data     = $request->validated();
        $password = Hash::make($data['password']);
        $reset_token = DB::table('password_reset_tokens')->where('token',$data['code']);
        if($reset_token->first())
        {
            DB::beginTransaction();
            User::where('email',$reset_token->first()->email)->first()->update([
                'password'=>$password
            ]);
            $reset_token->delete();
            DB::commit();
            return $this->dataResponse(null,__('password is updated successfully'),200);
        }
        return $this->dataResponse(null,__('code is invalid! please try again'),422);
    }

    public function submitToken(TokenRequest $request)
    {
    
       $data  = $request->validated();
       $token = Token::updateOrCreate(
            ['device_id' => $data['device_id']],
            [
                'user_id'     => $request->user()->id,
                'token'       => $data['token'],
                'device_type' => $data['device_type']
            ]
        );
    
        return $this->dataResponse(null,__('submitted successfully'),200);
    
      }
}
