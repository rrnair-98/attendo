<?php


namespace App\Services;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceBase
{
    protected $tableName;
    /**
     * Persists data in the respective model.
     * NOTE the instance variable $tableName must be given the correct name of the table for this to work.
     * @param $createdById The id of the person who created these objects
     * @throws \Exception When tablename or createdById is null
     * @param array $args Arguments to be inserted in the table
     */
    public function bulkInsert($createdById, array $args){
        if($createdById && $this->tableName){
            $now = Carbon::now('ist')->toDateTimeString();
            error_log(\GuzzleHttp\json_encode($args));
            foreach ($args as &$arg) {
                $arg['created_by'] = $createdById;
                $arg['created_at'] = $now;
                $arg['updated_at'] = $now;
            }
            error_log(\GuzzleHttp\json_encode($args));

            DB::beginTransaction();
            DB::table($this->tableName)->insert($args);
            DB::commit();
            return;
        }
        throw new \Exception("Bad request");
    }

    /**
     * Note-
     * Not adding findById and other helper methods in this class since an extra call would
     * be required to inflate respective model instances ie
     * ModelClass::hydrate($resultSet) must be called everytime a result set is obtained.
     */

}
