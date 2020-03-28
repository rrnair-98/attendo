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

class AttendanceExporter implements FromCollection, WithHeadings
{
    use Exportable;
    private $writerType = Excel::XLS;

    private $teacherLectureIds;

    public function withTeacherLectureIds(array $teacherLectureIds){
        $this->teacherLectureIds = $teacherLectureIds;
        return $this;
    }

    public function headings(): array
    {
        return ["Roll Number", "Name", "Percentage"];
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \ErrorException
     */
    public function collection()
    {
        if($this->teacherLectureIds == null || count($this->teacherLectureIds)== 0)
            throw new \ErrorException('illformated teacher lecture ids');
        $csvTeacherIds = implode(",", $this->teacherLectureIds);
        $query = sprintf(AttendanceTokenQuery::OPTIMIZED_FETCH_STUDENT_ATT_FOR_TEACHER_LECTURE_ID, $csvTeacherIds);
        return collect(DB::select($query));
    }
}
