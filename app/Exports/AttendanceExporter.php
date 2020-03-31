<?php

namespace App\Exports;

use App\Query\AttendanceTokenQuery;
use App\Query\TeacherLectureQuery;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use function foo\func;

class AttendanceExporter implements FromCollection, WithHeadings
{
    use Exportable;
    private $writerType = Excel::XLS;

    private $teacherLectureQuery;
    private $teacherLectureIds;
    private $lectures;
    private $lectureIndexMapping;

    public function __construct(TeacherLectureQuery $teacherLectureQuery)
    {
        $this->teacherLectureQuery = $teacherLectureQuery;
    }

    public function withTeacherLectureIds(array $teacherLectureIds){
        $this->teacherLectureIds = $teacherLectureIds;
        $this->lectures = $this->teacherLectureQuery->findAllByIdWithLectures($teacherLectureIds)->groupBy("teacher_lecture_id");
        $i=-1;
        foreach ($this->lectures->keys() as $key){
            $this->lectureIndexMapping[$key] = ++$i;
        }
        return $this;
    }

    public function headings(): array
    {
        $lectureNames = collect($this->lectures)->map(function ($lecture){return $lecture[0]->name;})->toArray();
        return array_merge(array_merge(["Roll Number", "Name"], $lectureNames), ["AVG"]);
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \ErrorException
     */
    public function collection()
    {
        if($this->teacherLectureIds == null || count($this->teacherLectureIds)== 0)
            throw new \ErrorException('illformated teacher lecture ids');
        return $this->fetchData($this->teacherLectureIds);
    }


    public function fetchData(array $teacherIds){
        $fromDate = Carbon::now()->firstOfYear();
        $toDate = Carbon::now();
        $collection = collect();
        foreach ($teacherIds as $teacherLectureId){
            $query = sprintf(AttendanceTokenQuery::OPTIMIZED_FETCH_STUDENT_ATT_FOR_TEACHER_LECTURE_ID,
                $teacherLectureId, $teacherLectureId, $teacherLectureId, $teacherLectureId, $fromDate, $toDate);
            $collection = $collection->concat(collect(DB::select($query)));
        }
        $collection = $collection->groupBy("id");
        return $this->formatRows($collection);
    }

    private function formatRows(Collection &$collection){
        $outCollection = collect();
        foreach ($collection->all() as $attendanceWithDifferentTeacherLectureIds){
            $row = array();
            error_log($attendanceWithDifferentTeacherLectureIds);
            $row[0] = $attendanceWithDifferentTeacherLectureIds->first()->roll_number;
            $row[1] = $attendanceWithDifferentTeacherLectureIds->first()->name;
            $avgVal =0 ;
            $i=0;
            $nullCounter = 0;
            foreach ($attendanceWithDifferentTeacherLectureIds as $attendance){
                $row[$this->lectureIndexMapping[$attendance->teacher_lecture_id]+ 2] = $attendance->percentage;
                ++$i;
                $nullCounter = $attendance->percentage == null? $nullCounter + 1 : $nullCounter;
                $avgVal+= $attendance->percentage;
            }

            array_push($row, ($avgVal/($i - $nullCounter) ));
            $outCollection->push($row);
        }
        return $outCollection;
    }
}
