<?php


namespace App\Transactors;

use App\AttendanceToken;
use App\Transactors\Mutations\AttendanceTokenMutator;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceTokenTransactor extends BaseTransactor
{
    private const CLASS_NAME = 'AttendanceTokenTransactor';
    private $attendanceMutator;
    public function __construct(AttendanceTokenMutator $mutator)
    {
        $this->attendanceMutator = $mutator;
    }

    /**
     * Creates an attendance token for the student
     * @param User $user
     * @return mixed
     * @throws \ErrorException
     * @throws \Throwable
     */
    public function create(User $user){
        try{
            DB::beginTransaction();
            $attendanceToken = $this->attendanceMutator->create(
                [
                    'created_by' => $user->id,
                    'expires_at' => Carbon::now()->addMinutes(AttendanceToken::MAX_EXPIRY_IN_MINUTES),
                    'token' => $this->attendanceMutator->generateToken($user)
                ]
            );
            DB::commit();
            return $attendanceToken;
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
     * Sets the is_present for each student token given.
     * @param $teacherId
     * @param $classLectureId
     * @param array $attendanceTokens
     * @throws \ErrorException
     * @throws \Throwable
     * @return bool true if all were set, false if otherwise.
     */
    public function markStudentsPresent($createdByUserId, $teacherLectureId, array $attendanceTokens){
        try{
            DB::beginTransaction();
            // could call bulk update...

            $updateCount = 0;
            $length = 0;
            foreach ($attendanceTokens as $token){
                $arr=['class_lecture_id' => $classLectureId, 'updated_by'=>$teacherId, 'is_present'=>1];
                $updateCount+=$this->attendanceMutator->update($token, $arr, 'token');
                ++$length;
            }
            DB::commit();
            return $updateCount == $length;
        } catch (ModelNotFoundException|\ErrorException $exception){
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error('Exception in '.self::CLASS_NAME.'@markStudentsPresent', ['message'=>
                $exception->getMessage(), 'trace'=>$exception->getTrace()]);
            throw $exception;
        }

    }

}
