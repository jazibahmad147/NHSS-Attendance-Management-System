<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class StudentsController extends Controller
{
    function register(Request $req){
        $response['success'] = array('success' => false, 'messages' => array());
        $student = new Student;
        $student->rollNumber = $req->rollNumber;
        $student->name = $req->name;
        $student->father = $req->father;
        $student->class = $req->class;
        $student->section = $req->section;
        // check data exists or not 
        $count = DB::table('students')
        ->where('rollNumber',$student->rollNumber)
        ->where('class',$student->class)
        ->where('section',$student->section)
        ->count();
        if($count==1){
            $response['success'] = false;
            $response['icon'] = "error";
            $response['title'] = "Failed";
            $response['messages'] = $req->rollNumber." Roll Number Already Registerd!";
        }else{
            $execute = $student->save();
            if($execute){
                $response['success'] = true;
                $response['icon'] = "success";
                $response['title'] = "Congratulations";
                $response['messages'] = $req->name."'s Record Registerd Successfully!";
            }else{
                $response['success'] = false;
                $response['icon'] = "warning";
                $response['title'] = "Failed";
                $response['messages'] = "Sorry! Something went wrong!";
            }
        }
        echo json_encode($response);
    }

    function view(Request $req){
        if ($req->isMethod('get')){
            $students = DB::table('students')->where('status',1)->get();
        }
        elseif ($req->isMethod('post')) {
            $class = $req->class;
            $section = $req->section;
            $students = DB::table('students')
            ->where('class',$class)
            ->where('section',$section)
            ->where('status',1)
            ->get();
        }
        $classes = DB::table('students')
            ->select('class')
            ->distinct()
            ->pluck('class');

        $sections = DB::table('students')
            ->select('section')
            ->distinct()
            ->pluck('section');
            
        return view('students', compact('students', 'classes', 'sections'));
    }
    
    function fetch($id){
        $record = DB::table('students')->where('id',$id)->get();
        echo json_encode($record);
    }

    function update(Request $req){
        $response['success'] = array('success' => false, 'messages' => array());
        $student = Student::find($req->id);
        $student->rollNumber = $req->rollNumber;
        $student->name = $req->name;
        $student->father = $req->father;
        $student->class = $req->class;
        $student->section = $req->section;
        $execute = $student->save();
        if($execute){
            $response['success'] = true;
            $response['icon'] = "success";
            $response['title'] = "Congratulations";
            $response['messages'] = $req->name."'s Record Updated Successfully!";
        }else{
            $response['success'] = false;
            $response['icon'] = "warning";
            $response['title'] = "Failed";
            $response['messages'] = "Sorry! Something went wrong!";
        }
        echo json_encode($response);
    }

    function delete($id){
        $update = DB::table('students')
        ->where('id',$id)
        ->update(['status' => 0]);
        if($update){
            $response = "Record Deleted Successfully!";
        }else{
            $response = "Sorry! Something went wrong!";
        }
        return redirect()->back()->with('response',$response);
    }

}

