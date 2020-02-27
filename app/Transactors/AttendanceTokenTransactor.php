<?php


namespace App\Transactors;

use App\AttendanceToken;
use App\Query\AttendanceTokenQuery;
use App\Query\TeacherLectureQuery;
use App\Transactors\Mutations\AttendanceTokenMutator;
use App\Transactors\Mutations\ClassLectureMutator;
use App\Transactors\Mutations\TeacherLectureMutator;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Uses the following mutators
 * 1. AttendanceMutator - To create attendance tokens, and to update the is_present flag when attendance is submitted
 * 2. ClassLectureMutator - To create class lectures when attendances are submitted
 * and the following queries
 * 1. AttendanceTokenQuery - To fetch a non expired token, To fetch a list of tokens of the student that have subscribed
 * to this class.
 * Class AttendanceTokenTransactor
 *
 * @package App\Transactors
 */
class AttendanceTokenTransactor extends BaseTransactor
{
    protected const CLASS_NAME = 'AttendanceTokenTransactor';
    private $attendanceMutator;
    private $attendanceTokenQuery;
    private $classLectureMutator;
    public function __construct(AttendanceTokenMutator $mutator, AttendanceTokenQuery $attendanceTokenQuery,
                    ClassLectureMutator $classLectureMutator)
    {
        $this->attendanceMutator = $mutator;
        $this->attendanceTokenQuery = $attendanceTokenQuery;
        $this->classLectureMutator = $classLectureMutator;
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

            //fetching valid token for student
            $existingAttendanceToken = $this->attendanceTokenQuery->getNonExpiredAttendanceTokenForStudent($user->id);
            if($existingAttendanceToken == null){
                DB::beginTransaction();
                // creating a new token
                $existingAttendanceToken = $this->attendanceMutator->create(
                    [
                        'created_by' => $user->id,
                        'expires_at' => Carbon::now()->addMinutes(AttendanceToken::MAX_EXPIRY_IN_MINUTES),
                        'token' => $this->attendanceMutator->generateToken($user)
                    ]
                );
                DB::commit();
            }
            return $existingAttendanceToken;
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
     * @param $createdByUserId ID This is equivalent to the teacher who initiated this call
     * @param $teacherLectureId ID Id of the lecture
     * @param array $attendanceTokens
     * @return integer Number of rows updated.
     *@throws \Throwable
     * @throws \ErrorException
     */
    public function markStudentsPresent($createdByUserId, $teacherLectureId, array $attendanceTokens){
        try{
            $length = count($attendanceTokens);
            DB::beginTransaction();
            $attendanceTokens =
                $this->attendanceTokenQuery
                    ->getValidAttendanceTokensFromList($attendanceTokens, $teacherLectureId)
                    ->map(function($x){return $x['token'];})->toArray();
            $teacherLecture = $this->classLectureMutator->create(['created_by'=>$createdByUserId,
                'teacher_lecture_id'=>$teacherLectureId]);
            $numRowsUpdated = 0;
            if(count($attendanceTokens) >0) {
                $numRowsUpdated = $this->attendanceMutator->updateBulk($attendanceTokens, ['is_present' => 1,
                    'class_lecture_id' => $teacherLecture->id, 'updated_by' => $createdByUserId ,
                    'expires_at'=>Carbon::now()->format('Y-m-d H:i:s')], 'token');
            }
            DB::commit();
            return $numRowsUpdated;
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
