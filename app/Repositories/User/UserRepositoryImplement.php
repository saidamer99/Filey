<?php

namespace App\Repositories\User;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;

class UserRepositoryImplement extends Eloquent implements UserRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register($request): array
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            if (!$user) {
                $data['status']=false;
                $data['code']=404;
                $data['message']="SomeThing Wrong happened";
                return $data;
            }
            $token = $user->createToken('token')->plainTextToken;
            $group= new Group();
            $publicGroup= $group->getPublicGroup();
            $publicGroup->members()->attach(['user_id'=>$user->id]);
            DB::commit();
            $data['status']=true;
            $data['code']=200;
            $data['message']="User Registered Successfully";
            $data['user']=$user;
            $data['token']=$token;

            return $data;

        } catch (\Throwable) {
            DB::rollBack();
            $data['status']=false;
            $data['code']=500;
            $data['message']="SomeThing Wrong happened";
            return $data;

        }

    }

    public function login($request): array
    {
        try {
            $user =User::where('email',$request->email)->first();
            if ($user) {
                if (!Hash::check($request->password, $user->password)) {
                    $data['status']=false;
                    $data['code']=500;
                    $data['message']='Invalid Password';

                    return $data;
                } else {
                    $token = $user->createToken('token')->plainTextToken;
                    $data['status']=true;
                    $data['code']=200;
                    $data['message']="User Logged in Successfully";
                    $data['user']=$user;
                    $data['token']=$token;
                    return $data;
                }
            }else{
                $data['status']=false;
                $data['code']=500;
                $data['message']='Invalid Email';
                return $data;
            }
        } catch (\Throwable) {
            $data['status']=false;
            $data['code']=500;
            $data['message']='SomeThing Wrong happened';
            return $data;
        }
    }
    public function logout(): array
    {
        try {
            $res = auth()->user()->tokens()->delete();
            $data['status']=true;
            $data['code']=200;
            $data['message']="Logged out Successfully";
            return $data;
        }catch (\Throwable)
        {
            $data['status']=false;
            $data['code']=500;
            $data['message']="Something Wrong happened";
            return $data;
        }
    }
}
