<?php
namespace App\Transactors;


use App\Helpers\Utils;
use App\Token;
use App\User;

class TokenService
{

    /**
     * @var int This value is to be added to refresh token expiry
     */
    private static $REFRESH_EXPIRY_TIME_IN_MILLIS = 6048000000000;
    /**
     * @var int This value is to be added to access token expiry
     */
    private static $ACCESS_TOKEN_EXPIRY_TIME_IN_MILLIS = 120000000000;


    /**
     * This method binds or creates token for the user.
     * This method is to be called everytime the user logs in.
     * @param User $user The user instance that must be bound to a token or whose token must be updated.
     * @return Token $token The token instance that was created or updated.
     */
    public function createOrRefresh(User $user)
    {
        $token = $this->getTokenByUser($user);
        if($token)
        {
            $this->setToken($token, $user);

            $token->save();
            return $token;

        }

        $token = new Token;
        $this->setToken($token, $user);
        $token->user_id = $user->id;
        $token->save();
        return $token;

    }


    public function refresh(Token $token)
    {
        $user = User::where('id', $token->user_id)->first();

        $this->setToken($token, $user);
        $token->save();
        return $token;
    }


    /**
     * @param User $user The user whose token is to be retrieved
     * @return Token A token instance that is bound to this user
     */
    public function getTokenByUser(User $user)
    {
        return Token::where("user_id", $user->id)->first();
    }

    /**
     * This method returns a user instance if the user access token is valid.
     * @param String $userAccessToken The token that the database must be queried with.
     * @return User user The user bound to the provided token.
     */
    public function getUserByAccessToken($userAccessToken)
    {
        $token = $this->getTokenByAccessToken($userAccessToken);
        return $token? $token->user(): null;
    }

    /**
     * This method returns a user instance if the user refresh token is valid
     * @param $refreshToken The token that th database must be queried with
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function getUserByRefreshToken($refreshToken){
        $token = $this->getTokenByRefreshToken($refreshToken);

        return $token ? $token->user() : null;
    }


    /**
     * This method returns a Token instance given the refresh token of the user.
     * @param $refreshToken
     * @return Token
     */
    public function getTokenByRefreshToken($refreshToken)
    {
        $token = Token::where("refresh_token", $refreshToken)->where("refresh_token_expiry", ">", Utils::getSystemTimeInMillis())->first();
        return $token;
    }

    /**
     * This method returns a Token instance given the access Token of the user.
     * @param $accessToken
     * @return Token
     */
    public function getTokenByAccessToken($accessToken)
    {
        return Token::where("access_token", $accessToken)->where("access_token_expiry", ">", Utils::getSystemTimeInMillis())->first();
    }

    public function deleteTokenByUserId($userId){
        return Token::where('user_id', $userId)->delete();
    }

    private function setToken(Token $token, User $user){
        $currentTimeMillis = Utils::getSystemTimeInMillis();

        $s = $user->id."".$user->email.time();
        $token->refresh_token = base64_encode(bcrypt($s));

        $token->refresh_token_expiry =  $currentTimeMillis + TokenService::$REFRESH_EXPIRY_TIME_IN_MILLIS;

        $s2 = $user->id . $user->email . time();

        $token->access_token = base64_encode(bcrypt($s2));
        $token->access_token_expiry = round(microtime(true) * 1000) + TokenService::$ACCESS_TOKEN_EXPIRY_TIME_IN_MILLIS;
    }




}
