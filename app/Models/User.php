<?php

namespace App\Models;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\Client as PassportClient;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // MARK: MUTATORS
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function tags(): Collection
    {
        return $this
            ->recipes()
            ->with('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->groupBy('id')
            ->map(function ($items) {
                /** @var Collection $items */

                /** @var Tag $first */
                $first = $items->first();

                $first->setAttribute('weight', $items->count());

                return $first;
            });
    }

    // MARK: Methods
    public function createAccessToken(string $rawPassword)
    {
        /** @var PassportClient $passport */
        $passport = PassportClient::where('password_client', 1)->first();

        $http = new GuzzleClient();
        $response = $http->post(URL::to('/oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $passport->getAttribute('id'),
                'client_secret' => $passport->getAttribute('secret'),
                'username' => $this->getAttribute('email'),
                'password' => $rawPassword,
                'scope' => '*'
            ]
        ]);

        $tokenInfo = Arr::only(
            json_decode((string)$response->getBody()),
            ['refresh_token', 'token_type']
        );

        $this->setAttribute('token', $tokenInfo);
    }

    /**
     * If access token exists recover it, else create a new.
     * For this time, if access token has expired request another access token.
     * @param string $rawPassword
     */
    public function loadAccessToken(string $rawPassword)
    {
        /**
         * 1. Recover password_client
         * @var PassportClient $passport
         */
        $passportClient = PassportClient::where('password_client', 1)->first();

//        /**
//         * 2. Recover access token
//         */
//        $accessToken = DB::table('oauth_access_tokens')
//            ->where('user_id', $this->getAttribute('id'))
//            ->where('client_id', $passportClient->getAttribute('id'))
//            ->first();
//
//        if (is_null($accessToken)) {
//
//            $formParams = [
//                'grant_type' => 'password',
//                'client_id' => $passportClient->getAttribute('id'),
//                'client_secret' => $passportClient->getAttribute('secret'),
//                'username' => $this->getAttribute('email'),
//                'password' => $rawPassword,
//                'scope' => '*'
//            ];
//
//            $http = new GuzzleClient();
//            $response = $http->post(URL::to('/oauth/token'), [
//                'form_params' => $formParams
//            ]);
//
//            $tokenInfo = Arr::only(
//                json_decode((string)$response->getBody(), true),
//                ['access_token', 'expires_in']
//            );
//
//        } else if (Carbon::create($accessToken->expires_at)->lte(Carbon::now())) {
//
////            /**
////             * 3. Recover refresh token
////             */
////            $refreshToken = DB::table('oauth_refresh_tokens')
////                ->where('access_token_id', $accessToken->id)
////                ->where('revoked', 0)
////                ->first();
//
//
////            if (!is_null($refreshToken)) {
////
////                $this->setAttribute('token', $this->refresh_token);
////                return;
////
////                $formParams = [
////                    'grant_type' => 'refresh_token',
////                    'refresh_token' => $refreshToken->id,
////                    'client_id' => $passportClient->getAttribute('id'),
////                    'client_secret' => $passportClient->getAttribute('secret'),
////                    'scope' => ''
////                ];
////
////            } else {
////
////                $formParams = [
////                    'grant_type' => 'password',
////                    'client_id' => $passportClient->getAttribute('id'),
////                    'client_secret' => $passportClient->getAttribute('secret'),
////                    'username' => $this->getAttribute('email'),
////                    'password' => $rawPassword,
////                    'scope' => ''
////                ];
////            }
//
//            $http = new GuzzleClient();
//
//            $formParams = [
//                'grant_type' => 'password',
//                'client_id' => $passportClient->getAttribute('id'),
//                'client_secret' => $passportClient->getAttribute('secret'),
//                'username' => $this->getAttribute('email'),
//                'password' => $rawPassword,
//                'scope' => '*'
//            ];
//
//            $response = $http->post(URL::to('/oauth/token'), ['form_params' => $formParams]);
//
//            $tokenInfo = Arr::only(
//                json_decode((string)$response->getBody(), true),
//                ['access_token', 'expires_in']
//            );
//
//        } else {
//            $expiresIn = Carbon::create($accessToken->expires_at)->diffInSeconds(Carbon::now());
//
//            $tokenInfo = [
//                'access_token' => $accessToken->id,
//                'expires_in' => $expiresIn,
//            ];
//        }

        $formParams = [
            'grant_type' => 'password',
            'client_id' => $passportClient->getAttribute('id'),
            'client_secret' => $passportClient->getAttribute('secret'),
            'username' => $this->getAttribute('email'),
            'password' => $rawPassword,
            'scope' => '*'
        ];

        $http = new GuzzleClient();
        $response = $http->post(URL::to('/oauth/token'), [
            'form_params' => $formParams
        ]);

        $tokenInfo = Arr::only(
            json_decode((string)$response->getBody(), true),
            ['access_token', 'expires_in']
        );

        $this->setAttribute('token', $tokenInfo);
    }
}
