<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    function searchStudentsAttendanceReports(Request $req){
        if ($req->isMethod('get')){
            
            $students = DB::table('students')
                ->leftJoin('attendances', 'students.id', '=', 'attendances.studentId')
                ->select(
                    'students.name',
                    'students.father',
                    'students.class',
                    'students.section',
                    'students.rollNumber',
                    DB::raw('COUNT(CASE WHEN attendances.attendanceStatus = "P" THEN 1 END) as total_presents'),
                    DB::raw('COUNT(CASE WHEN attendances.attendanceStatus = "A" THEN 1 END) as total_absents'),
                    DB::raw('COUNT(CASE WHEN attendances.attendanceStatus = "L" THEN 1 END) as total_leaves')
                )
                ->groupBy('students.id', 'students.name', 'students.father', 'students.class', 'students.section', 'students.rollNumber')
                ->get();
            // find violations corropond to the student 
            foreach ($students as $student){
                $rollNumber = $student->rollNumber;
                $class = $student->class;
                $section = $student->section;

                $violationData = DB::table('students')
                    ->leftJoin('violations', 'students.id', '=', 'violations.studentId')
                    ->select(
                        'students.name',
                        'students.father',
                        'students.class',
                        'students.section',
                        'students.rollNumber',
                        DB::raw('COUNT(CASE WHEN violations.status = "Late" THEN 1 END) as total_late'),
                        DB::raw('COUNT(CASE WHEN violations.status = "Incomplete Uniform" THEN 1 END) as total_incomplete_uniform'),
                        DB::raw('COUNT(CASE WHEN violations.status = "Late + Incomplete Uniform" THEN 1 END) as total_late_incomplete_uniform'),
                    )
                    ->where('students.rollNumber', $rollNumber)
                    ->where('students.class', $class)
                    ->where('students.section', $section)
                    ->groupBy('students.id', 'students.name', 'students.father', 'students.class', 'students.section', 'students.rollNumber')
                    ->get();

                // Add total violations 
                $student->total_late = $violationData[0]->total_late;
                $student->total_incomplete_uniform = $violationData[0]->total_incomplete_uniform;
                $student->total_late_incomplete_uniform = $violationData[0]->total_late_incomplete_uniform;
            }

            $classes = DB::table('students')
                ->select('class')
                ->distinct()
                ->pluck('class');

            $sections = DB::table('students')
                ->select('section')
                ->distinct()
                ->pluck('section');

            return view('report', compact('classes', 'sections', 'students'));
        }
        elseif ($req->isMethod('post')) {
            $class = $req->class;
            $section = $req->section;
            $startDate = $req->startDate;
            $endDate = $req->endDate;

             
            $students = DB::table('students')
                ->leftJoin('attendances', 'students.id', '=', 'attendances.studentId')
                ->select(
                    'students.name',
                    'students.father',
                    'students.class',
                    'students.section',
                    'students.rollNumber',
                    DB::raw('COUNT(CASE WHEN attendances.attendanceStatus = "P" THEN 1 END) as total_presents'),
                    DB::raw('COUNT(CASE WHEN attendances.attendanceStatus = "A" THEN 1 END) as total_absents'),
                    DB::raw('COUNT(CASE WHEN attendances.attendanceStatus = "L" THEN 1 END) as total_leaves')
                )
                ->where('students.class', $class)
                ->where('students.section', $section)
                ->groupBy('students.id', 'students.name', 'students.father', 'students.class', 'students.section', 'students.rollNumber')
                ->get();

            // find violations corropond to the student 
            foreach ($students as $student){
                $rollNumber = $student->rollNumber;
                $class = $student->class;
                $section = $student->section;

                $violationData = DB::table('students')
                    ->leftJoin('violations', 'students.id', '=', 'violations.studentId')
                    ->select(
                        'students.name',
                        'students.father',
                        'students.class',
                        'students.section',
                        'students.rollNumber',
                        DB::raw('COUNT(CASE WHEN violations.status = "Late" THEN 1 END) as total_late'),
                        DB::raw('COUNT(CASE WHEN violations.status = "Incomplete Uniform" THEN 1 END) as total_incomplete_uniform'),
                        DB::raw('COUNT(CASE WHEN violations.status = "Late + Incomplete Uniform" THEN 1 END) as total_late_incomplete_uniform'),
                    )
                    ->where('students.rollNumber', $rollNumber)
                    ->where('students.class', $class)
                    ->where('students.section', $section)
                    ->whereBetween('violations.date', [$startDate, $endDate])
                    ->groupBy('students.id', 'students.name', 'students.father', 'students.class', 'students.section', 'students.rollNumber')
                    ->get();

                // Add total violations 
                if (count($violationData)>0){
                    $student->total_late = $violationData[0]->total_late;
                    $student->total_incomplete_uniform = $violationData[0]->total_incomplete_uniform;
                    $student->total_late_incomplete_uniform = $violationData[0]->total_late_incomplete_uniform;
                }else{
                    $student->total_late = 0;
                    $student->total_incomplete_uniform = 0;
                    $student->total_late_incomplete_uniform = 0;
                }
            }

            $classes = DB::table('students')
                ->select('class')
                ->distinct()
                ->pluck('class');

            $sections = DB::table('students')
                ->select('section')
                ->distinct()
                ->pluck('section');

            // return $students;
            return view('report', compact('classes', 'sections', 'students'));

        }
    }
}
