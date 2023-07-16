<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Violation;

class ViolationsController extends Controller
{
    function fetchClassSection(){
        $classes = DB::table('students')
            ->select('class')
            ->distinct()
            ->pluck('class');

        $sections = DB::table('students')
            ->select('section')
            ->distinct()
            ->pluck('section');

        // Data for tabel 
        $record = DB::table('students')
        ->join('violations', 'students.id', '=', 'violations.studentId')
        ->select('students.rollNumber', 'students.name', 'students.father', 'students.class', 'students.section', 'violations.id', 'violations.date', 'violations.status')
        ->get();
        
        return view('rule-violations', compact('classes', 'sections','record'));
    }

    function save(Request $req){
        
        $rollNumber = $req->rollNumber;
        $class = $req->class;
        $section = $req->section;
        $violations = $req->violations;
        $date = $req->date;
        
        // fetch studentId
        $studentRecord = DB::table('students')
        ->where('rollNumber', $rollNumber)
        ->where('class', $class)
        ->where('section', $section)
        ->get();
        $studentId = $studentRecord[0]->id;

        // check students is present or not?
        $count = DB::table('attendances')
        ->where('date',$date)
        ->where('studentId',$studentId)
        ->where('attendanceStatus','P')
        ->count();
        if($count > 0){
            $count = DB::table('violations')
            ->where('date',$date)
            ->where('studentId',$studentId)
            ->count();
            if($count==1){
                $execute = Violation::where('studentId', $studentId)
                    ->where('date', $date)
                    ->update(['status' => $violations]);
            
                $response['success'] = true;
                $response['icon'] = "success";
                $response['title'] = "Congratulations";
                $response['messages'] = "Record Updated Successfully!";
            }else{
                $execute = DB::insert('INSERT INTO `violations` (`date`,`studentId`,`status`) values(?,?,?)',[$date,$studentId,$violations]);
                if($execute){
                    $response['success'] = true;
                    $response['icon'] = "success";
                    $response['title'] = "Congratulations";
                    $response['messages'] = "Record Added Successfully!";
                }else{
                    $response['success'] = false;
                    $response['icon'] = "warning";
                    $response['title'] = "Failed";
                    $response['messages'] = "Sorry! Something went wrong!";
                }
            }
        }else{
            $response['success'] = false;
            $response['icon'] = "warning";
            $response['title'] = "Failed";
            $response['messages'] = "Sorry! Student status is not present.";
        }
        
        echo json_encode($response);
    }

    function delete($id){
        // return $id;
        $delete = DB::table('violations')
        ->where('id', $id)
        ->delete();
        if($delete){
            $response = "Record Deleted Successfully!";
        }else{
            $response = "Sorry! Something went wrong!";
        }
        return redirect()->back()->with('response',$response);
    }
}
