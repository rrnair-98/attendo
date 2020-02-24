<?php


namespace App\Transactors;


use App\Query\UserQuery;
use App\Services\TokenService;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthTransactor extends BaseTransactor
{
    private $userQuery;
    private $tokenService;

    public function __construct(UserQuery $userQuery, TokenService $tokenService){
        $this->userQuery = $userQuery;
        $this->tokenService = $tokenService;
    }

    /**
     * Creates tokens for the user with the given combo
     * @param string $email
     * @param string $password
     * @return \App\Token
     * @throws \ErrorException
     */
    public function login(string $email, string $password){
        try{
            DB::beginTransaction();

            $user =$this->userQuery->getUserByEmailAndPassword($email, $password);
            $token = $this->tokenService->createOrRefresh($user);
            DB::commit();
            return $token;
        } catch (ModelNotFoundException|\ErrorException $exception){
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error('Exception in '.self::CLASS_NAME.'@'.self::METHOD_CREATE, ['message'=>
                $exception->getMessage(), 'trace'=>$exception->getTrace()]);
            throw $exception;
        }
    }

    /**
     * Deletes a token instance
     * @param User $user
     * @throws \ErrorException
     */
    public function logout(User $user){
        try{
            DB::beginTransaction();
            $this->tokenService->deleteTokenByUserId($user->id);
            DB::commit();
        } catch (ModelNotFoundException|\ErrorException $exception){
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error('Exception in '.self::CLASS_NAME.'@'.self::METHOD_CREATE, ['message'=>
                $exception->getMessage(), 'trace'=>$exception->getTrace()]);
            throw $exception;
        }
    }


    /**
     * Refreshes the token given the access token.
     * @param string $refreshToken
     * @return \App\Token
     * @throws \ErrorException
     */
    public function refresh(string $refreshToken){
        try{
            DB::beginTransaction();

            $token = $this->tokenService->getTokenByRefreshToken($refreshToken);
            $token = $this->tokenService->refresh($token);
            DB::commit();
            return $token;
        } catch (ModelNotFoundException|\ErrorException $exception){
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error('Exception in '.self::CLASS_NAME.'@'.self::METHOD_CREATE, ['message'=>
                $exception->getMessage(), 'trace'=>$exception->getTrace()]);
            throw $exception;
        }
    }

    /*public function general(){
        try{
            DB::beginTransaction();


            DB::commit();
        } catch (ModelNotFoundException|\ErrorException $exception){
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error('Exception in '.self::CLASS_NAME.'@'.self::METHOD_CREATE, ['message'=>
                $exception->getMessage(), 'trace'=>$exception->getTrace()]);
            throw $exception;
        }
    }*/





}
