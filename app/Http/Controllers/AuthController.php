<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Laravel\Passport\TokenRepository;

class AuthController extends Controller
{
    use ApiResponser;

    public const PROVIDERS = ['github', 'twitter', 'google'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:8|',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return $this->error('erreur', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }

        if (!Auth::attempt($validator->validated())) {
            return $this->error('Vos identifiants ne correspondent pas', 401);
        }
//        if (!Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
//            return $this->error('Vos identifiants ne correspondent pas', 401);
//        }

        return $this->success([
            'token' => auth()->user()->createToken('api_token')->plainTextToken
        ], "succes", Response::HTTP_OK);
//        return $this->success([
//            'token' => auth()->user()->createToken('api_token')->plainTextToken
//        ]);
    }

    public function me()
    {
        if (auth()->user()) {
            $user = auth()->user();
            return $this->success($user, 'Connected user!');
        } else {
            return $this->error("Invalid token", Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout (Request $request) {
        $token = $request->bearerToken();
        $tokenId = (new Parser(new JoseEncoder()))->parse($token)->claims()->all()['jti'];
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($tokenId);

        $response = ['message' => 'You have been successfully logged out!'];
        return $this->success($response);
    }

    public function socialLogin($provider)
    {
        if(!in_array($provider, self::PROVIDERS)){
            return $this->error("The $provider provider is not allow.");
        }

        if ($provider == 'twitter'){
            return Socialite::driver($provider)->redirect();
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function githubLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error('erreur', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }

        $access_token = $validator->validated()['access_token'];
        $providerUser = Socialite::driver('github')->userFromToken($access_token);

//        $providerUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate([
            'provider_id' => $providerUser->id,
        ], [
            'name' => $providerUser->name,
            'email' => $providerUser->email,
            'github_token' => $providerUser->token,
            'github_refresh_token' => $providerUser->refreshToken,
        ]);

//        $user->markEmailAsVerified();

        Auth::login($user);

        $user = Auth::user();
        return $this->success([
            'token' => $user->createToken('api_token')->accessToken
        ], "User connected!");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function githubLogin1(Request $request)
    {
        $providerUser = Socialite::driver('github')->stateless()->user();


//        $providerUser = Socialite::driver('github')->user();

        $user = User::where('email', $providerUser->email)->first();
        if (!isset($user)){
            $user = User::updateOrCreate([
                'provider_id' => $providerUser->id,
            ], [
                'provider_name' => 'github',
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'nickname' => $providerUser->nickname,
                'username' => $providerUser->nickname,
                'avatar' => $providerUser->avatar,
                'expiresIn' => $providerUser->expiresIn,
                'token' => $providerUser->token,
                'refresh_token' => $providerUser->refreshToken,
            ]);
        }


        Auth::login($user);

        $user = Auth::user();
        return $this->success([
            'token' => $user->createToken('api_token')->accessToken,
            'user' => $user
        ], "User connected!");
    }

    public function googleLogin(Request $request)
    {
        $providerUser = Socialite::driver('google')->stateless()->user();


//        $providerUser = Socialite::driver('github')->user();

        $user = User::where('email', $providerUser->email)->first();
        if (!isset($user)){
            $user = User::updateOrCreate([
                'provider_id' => $providerUser->id,
            ], [
                'provider_name' => 'google',
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'nickname' => $providerUser->nickname,
                'username' => $providerUser->nickname,
                'avatar' => $providerUser->avatar,
                'expiresIn' => $providerUser->expiresIn,
                'token' => $providerUser->token,
                'refresh_token' => $providerUser->refreshToken,
            ]);
        }

        Auth::login($user);

        $user = Auth::user();
        return $this->success([
            'token' => $user->createToken('api_token')->accessToken,
            'user' => $user
        ], "User connected!");
    }

    public function twitterLogin(Request $request)
    {
        $providerUser = Socialite::driver('twitter')->stateless()->user();


//        $providerUser = Socialite::driver('github')->user();
//        return $this->success($providerUser);
        $user = User::updateOrCreate([
            'provider_id' => $providerUser->id,
        ], [
            'provider_name' => 'twitter',
            'name' => $providerUser->name,
            'email' => $providerUser->email,
            'nickname' => $providerUser->nickname,
            'username' => $providerUser->nickname,
            'avatar' => $providerUser->avatar,
            'expiresIn' => $providerUser->expiresIn,
            'token' => $providerUser->token,
            'refresh_token' => $providerUser->refreshToken,
        ]);

        Auth::login($user);

        $user = Auth::user();
        return $this->success([
            'token' => $user->createToken('api_token')->accessToken,
            'user' => $user
        ], "User connected!");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
