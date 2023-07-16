<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;

class AttendancesController extends Controller
{
    function viewClasses(){
        $record = DB::table('students')
        ->select('class','section')
        ->distinct()
        ->get();
        return view('attendances',['classes'=>$record]);
        // return ($record);
    }

    function viewNewAttendanceTable($class,$section){
        // return ($class.$section);
        $record = DB::table('students')
        ->where('class',$class)
        ->where('section',$section)
        ->get();
        return view('take-attendance',['records'=>$record]);
    }

    function fetchOldAttendance(Request $req){
        $date = $req->date;
        $class = $req->class;
        $section = $req->section;
        $record = DB::table('students')
        ->leftJoin('attendances', 'students.id', '=', 'attendances.studentId')
        ->where('date',$date)
        ->where('class',$class)
        ->where('section',$section)
        ->get();
        return $record;
    }

    function viewAttendanceRegister(Request $req,$class,$section){
        if ($req->isMethod('get')) {
            $record = DB::table('students')
            ->leftJoin('attendances', 'students.id', '=', 'attendances.studentId')
            ->where('class',$class)
            ->where('section',$section)
            ->orderBy('date','asc')
            ->get();
        } 
        elseif ($req->isMethod('post')) {
            $monthAndYear = $req->date;
            list($year, $month) = explode('-', $monthAndYear);
            $startDate = date('Y-m-01', strtotime($monthAndYear));
            $endDate = date('Y-m-t', strtotime($monthAndYear));

            $record = DB::table('students')
            ->leftJoin('attendances', 'students.id', '=', 'attendances.studentId')
            ->where('class',$class)
            ->where('section',$section)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->orderBy('date','asc')
            ->get();
        }
        
        $dates = array();
        $rollNumbers = array();
        $date = '';
        for ($i=0; $i < count($record); $i++) { 
            if($record[$i]->date != $date){
                // echo $record[$i]->date."<br>";
                array_push($dates,$record[$i]->date);
            }
            $date = $record[$i]->date;
            array_push($rollNumbers,$record[$i]->rollNumber);
        }
        $uniqueRollNumbers = array_unique($rollNumbers);
        return view('attendance-register',['class'=>$class,'section'=>$section,'records'=>$record,'dates'=>$dates,'rollNumbers'=>$uniqueRollNumbers]);
    }

    function save(Request $req){
        // $attendance = new Attendance;
        $date = $req->date;
        for ($i=0; $i < count($req->studentId); $i++) { 
            $studentId = $req->studentId[$i];
            $string = "attendanceStatus".$studentId;
            $attendanceStatus = $req->$string;
            // check data exists or not 
            $count = DB::table('attendances')
            ->where('date',$date)
            ->where('studentId',$studentId)
            ->count();
            if($count==1){
                $response['success'] = false;
                $response['icon'] = "error";
                $response['title'] = "Failed";
                $response['messages'] = "Attendance Already Marked!";
            }else{
                $execute = DB::insert('INSERT INTO `attendances` (`date`,`studentId`,`attendanceStatus`) values(?,?,?)',[$date,$studentId,$attendanceStatus]);
                if($execute){
                    $response['success'] = true;
                    $response['icon'] = "success";
                    $response['title'] = "Congratulations";
                    $response['messages'] = "Attendance Done Successfully!";
                }else{
                    $response['success'] = false;
                    $response['icon'] = "warning";
                    $response['title'] = "Failed";
                    $response['messages'] = "Sorry! Something went wrong!";
                }
            }
        }
        echo json_encode($response);
    }

    function update(Request $req){
        $date = $req->date;
        for ($i=0; $i < count($req->recordId); $i++) { 
            $recordId = $req->recordId[$i];
            $string = "attendanceStatus".$recordId;
            $attendanceStatus = $req->$string;
            $execute = Attendance::where('id', $recordId)->update(['attendanceStatus' => $attendanceStatus]);
            
            $response['success'] = true;
            $response['icon'] = "success";
            $response['title'] = "Congratulations";
            $response['messages'] = "Attendance Updated Successfully!";
            
            
        }
        echo json_encode($response);
    }

    function searchStudentsAttendance(Request $req){
        if ($req->isMethod('get')){
            $classes = DB::table('students')
                ->select('class')
                ->distinct()
                ->pluck('class');

            $sections = DB::table('students')
                ->select('section')
                ->distinct()
                ->pluck('section');

            return view('search-student-attendance', compact('classes', 'sections'));
        }
        elseif ($req->isMethod('post')) {
            $rollNumber = $req->rollNumber;
            $class = $req->class;
            $section = $req->section;
            $startDate = $req->startDate;
            $endDate = $req->endDate;
    
            $attendanceData = DB::table('students')
                ->leftJoin('attendances', 'students.id', '=', 'attendances.studentId')
                ->select(
                    'students.name',
                    'students.father',
                    'students.class',
                    'students.section',
                    'students.rollNumber',
                    DB::raw('COUNT(CASE WHEN attendances.attendanceStatus = "A" THEN 1 END) as total_absents'),
                    DB::raw('COUNT(CASE WHEN attendances.attendanceStatus = "L" THEN 1 END) as total_leaves'),
                    DB::raw('GROUP_CONCAT(CASE WHEN attendances.attendanceStatus = "A" THEN attendances.date END) as absent_dates'),
                    DB::raw('GROUP_CONCAT(CASE WHEN attendances.attendanceStatus = "L" THEN attendances.date END) as leave_dates')
                )
                ->where('students.rollNumber', $rollNumber)
                ->where('students.class', $class)
                ->where('students.section', $section)
                ->whereBetween('attendances.date', [$startDate, $endDate])
                ->groupBy('students.id', 'students.name', 'students.father', 'students.class', 'students.section', 'students.rollNumber')
                ->get();
    
            $violationData = DB::table('students')
                ->leftJoin('violations', 'students.id', '=', 'violations.studentId')
                ->select(
                    DB::raw('COUNT(CASE WHEN violations.status = "Late" THEN 1 END) as total_late'),
                    DB::raw('COUNT(CASE WHEN violations.status = "Incomplete Uniform" THEN 1 END) as total_incomplete_uniform'),
                    DB::raw('COUNT(CASE WHEN violations.status = "Late + Incomplete Uniform" THEN 1 END) as total_late_incomplete_uniform'),
                    DB::raw('GROUP_CONCAT(CASE WHEN violations.status = "Late" THEN violations.date END) as late_dates'),
                    DB::raw('GROUP_CONCAT(CASE WHEN violations.status = "Incomplete Uniform" THEN violations.date END) as incomplete_uniform_dates'),
                    DB::raw('GROUP_CONCAT(CASE WHEN violations.status = "Late + Incomplete Uniform" THEN violations.date END) as late_incomplete_uniform_dates')
                )
                ->where('students.rollNumber', $rollNumber)
                ->where('students.class', $class)
                ->where('students.section', $section)
                ->whereBetween('violations.date', [$startDate, $endDate])
                ->groupBy('students.id', 'students.name', 'students.father', 'students.rollNumber')
                ->get();
    
            $data = $attendanceData->merge($violationData);
    
    
            $classes = DB::table('students')
                ->select('class')
                ->distinct()
                ->pluck('class');
    
            $sections = DB::table('students')
                ->select('section')
                ->distinct()
                ->pluck('section');
    
            $attendanceRecord = $data[0];
            $violationRecord = $data[1];
            return view('search-student-attendance', compact('classes', 'sections', 'attendanceRecord','violationRecord'));
        }
    }

}
