<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
use App\Permission;

use App\Events\UserConnected;
use App\Events\ChannelEdited;
use App\Events\UserChanged;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Role as RoleResource;

use DB;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller 
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', 'description' => 'Incorrect email or password'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    // public $loginAfterSignUp = true;

    function register(Request $request){
        $user = new User;

        $this->validate($request, [
            "name" => "unique:users|required|min:1|max:32",
            "email" => "unique:users|required|email",
            "password" => "required|min:6|max:32|regex:/^(\w*\d*[^\s]*)$/",
        ]);

        $name = $request->name;
        $email    = $request->email;
        $password = $request->password;
        $user = User::create(['name' => $name, 'email' => $email, 'image' => '/images/blank.png', 'password' => Hash::make($password)]);
        $user->save();

        $this->generateImageFromName($user);

        $user->channels()->attach(1);
        $user->roles()->attach(3);

        $channel = $user->channels()->first();
        event(new ChannelEdited($channel));

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    function test()
    {
        
        // event(new App\Events\UserConnected());
        event(new UserConnected());
        // Event::fire(app()->make('App\Events\UserConnected'));

        return response()->json(['data' => 'Test Data']);
    }

    function getUser(Request $request){
        $user = Auth::user();

        // $rolePerm = $user->roles()->first()->permissions()->get();
        $rolePerm = new \Illuminate\Database\Eloquent\Collection([]);
        $userRoles = $user->roles();
        foreach ($userRoles->get() as $role) {
            $rolePerm = $rolePerm->merge($role->permissions()->get());
        }
        // $rolePerm = $user->roles()->first()->permissions()->get();
        $userPerm = $user->permissions()->get();
        $perms = $rolePerm->merge($userPerm);
        $perms = $perms->sortBy('id')->values();
        $user['permissions'] = $perms;

        return $user;
    }

    function getUsers(Request $request){
        // return User::all()->getUserData();
        return UserResource::collection(User::all());
    }

    function editUserEmail(Request $request){
        $user = Auth::user();

        // dd($request);

        $this->validate($request, [
            "email" => "unique:users|required|email",
        ]);

        $user->email = $request->email;
        $user->save();

        return response($user, 200);
    }

    function editUserPassword(Request $request){
        $user = Auth::user();

        $this->validate($request, [
            "password" => "required|min:6|max:32|regex:/^(\w*\d*[^\s]*)$/",
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return response($user, 200);
    }

    public function setUserPrivileges(Request $request, $userId){
        $user = User::findOrFail($userId);

        $user->roles()->sync($request->roles);
        $user->permissions()->sync($request->permissions);

        event(new UserChanged($user));

        return new UserResource($user);
    }

    public function resetUserImage(Request $request){
        $user = Auth::user();
        
        $this->generateImageFromName($user);

        // event(new UserChanged($user));

        return response($user, 200);
    }

    function generateImageFromName($user){
        $name = $user->name;
        $color = [
            'r' => 0,
            'g' => 0,
            'b' => 0,
        ];
        $hash = md5($name);
        $color['r'] = (hexdec(substr($hash, 0, 2))/255)*100;
        $color['g'] = (hexdec(substr($hash, 2, 2))/255)*100;
        $color['b'] = (hexdec(substr($hash, 4, 2))/255)*100;

        // dd(Storage::disk('public')->path('images/blank.png'));
        // dd(storage_path('images/blank.png'));
        // dd(storage_path('images/blank.png'));

        // dd($color);

        $img = Image::make(file_get_contents('images/blank.png'));
        $img->colorize($color['r'], $color['g'], $color['b']);
        $img->fit(256);

        $img->save(Storage::disk('public')->path('images/users/' . $user->id . '.png'));

        $user->image ='/storage/images/users/' . $user->id . '.png';
        $user->save();

        // return $img->response();
    }

    public function updateUserImage(Request $request){
        $user = Auth::user();

        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,gif,webp|max:8192'
        ]);

        if ($request->has('file')) {
            $file = $request->file('file');

            $path = '/storage/images/users/' . $user->id . '.png';
            $filePath = Storage::disk('public')->path('images/users/' . $user->id . '.png');

            $img = Image::make($file);
            $img->fit(256);
            $img->encode('png');
            $img->save($filePath);

            $user->image = $path;

            $user->save();
        }

        return response($user, 200);
    }
}