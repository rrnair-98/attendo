<?php

namespace App\Exports;

use App\Query\AttendanceTokenQuery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceExporter implements FromCollection
{
    private $writerType = Excel::CSV;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    private $teacherLectureIds;

    public function setTeacherLectureIds(array $teacherLectureIds){
        $this->teacherLectureIds = $teacherLectureIds;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->teacherLectureIds == null || count($this->teacherLectureIds)== 0)
            throw new \ErrorException('illformated teacher lecture ids');
        $csvTeacherIds = implode(",", $this->teacherLectureIds);
        $num = DB::raw();
        $query = sprintf(AttendanceTokenQuery::OPTIMIZED_FETCH_STUDENT_ATT_FOR_TEACHER_LECTURE_ID, );
    }
}
