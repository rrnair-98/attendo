<?php


namespace App\Services;

use App\AccessToken;
use App\User;
use DateTime;
use Illuminate\Support\Str;

/**
 * Class TokenService
 * @package App\Services
 */
class TokenService
{
    /**
     * Create a Token given
     * @param User $user
     * @return AccessToken
     */
    public function generateToken(User $user): AccessToken{

        $arr = $this->getTokenFieldsInArray($user->id);
        return AccessToken::create(['user_id'=>$user->id, 'created_at'=>$arr[0], 'expires_at'=>$arr[1],
            'refresh_token_expires_at'=>$arr[2], 'token'=>$arr[3],
            'refresh_token'=>$arr[4]]);
    }

    /**
     * Generates and updates a row in AccessToken table given the refresh token.
     * @param $refreshToken
     * @return AccessToken| null
     */
    public function refreshToken($refreshToken){
        $now =  round(microtime(true) * 1000);
        $authToken = AccessToken::where('refresh_token', '=', $refreshToken)->where('refresh_token_expires_at', '>', $now)->with('user')->first();
        if ($authToken){
            $arr = $this->getTokenFieldsInArray($authToken->user_id);
            $authToken->update(['updated_at'=>$arr[0],'expires_at'=>$arr[1],
            'refresh_token_expires_at'=>$arr[2], 'token'=>$arr[3],
            'refresh_token'=>$arr[4]]);
            return $authToken;
        }
        return $authToken;
    }

    /**
     * Finds an AccessToken with this token string.
     * @param string $token The token to query the table with
     * @return AccessToken
     */
    public function getAccessTokenWithTokenString(string $token) {
        $now =  round(microtime(true) * 1000);
        return AccessToken::where('token', '=', $token)->where('expires_at', '>', $now)->with('user')->first();
    }

    /**
     * Revokes a token by deleting it given the token string.
     * @param string $token The token string to query the table with
     */
    public function revokeToken(string $token){
        $token = AccessToken::where('token', '=', $token)->first();
        $token->forceDelete();
    }

    /**
     * Generates the fields required for creating an AccessToken.
     * NOTE this method is to be called while generating a new token or while refreshing an AccessToken instance.
     * @param string arg Some random value to be added to the AccessToken.
     * @return array
     */
    private function getTokenFieldsInArray(string $arg = "2r23ewasd"){
        $now = round(microtime(true) * 1000);
        $expiry = $now + AccessToken::$ACESS_TOKEN_DURATION_MILLISECONDS;
        $refreshTokenExpiry = $now + AccessToken::$REFRESH_TOKEN_DURATION_MILLISECONDS;
        $accessTokenString = hash('sha256', $arg.$now.Str::random(10));
        $refreshTokenString = hash('sha256', $arg.$now.Str::random(10));
        return [$now, $expiry, $refreshTokenExpiry, $accessTokenString, $refreshTokenString];
    }


}
