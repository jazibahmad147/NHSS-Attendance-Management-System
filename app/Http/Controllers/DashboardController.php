<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function dashboard(){
        $today = date('Y-m-d');

        $attendanceCounts = DB::table('attendances')
            ->select(
                DB::raw('COUNT(CASE WHEN attendanceStatus = "P" THEN 1 END) as totalPresent'),
                DB::raw('COUNT(CASE WHEN attendanceStatus = "A" THEN 1 END) as totalAbsent'),
                DB::raw('COUNT(CASE WHEN attendanceStatus = "L" THEN 1 END) as totalLeave')
            )
            ->where('date', $today)
            ->first();

        $violationsCounts = DB::table('violations')
            ->select(
                DB::raw('COUNT(CASE WHEN status = "Late" THEN 1 END) as totalLate'),
                DB::raw('COUNT(CASE WHEN status = "Incomplete Uniform" THEN 1 END) as totalIncompleteUniform'),
                DB::raw('COUNT(CASE WHEN status = "Late + Incomplete Uniform" THEN 1 END) as totalLateIncompleteUniform')
            )
            ->where('date', $today)
            ->first();

        $totalStudents = DB::table('students')->where('status', 1)->count();
        // handle cases where there are no matching rows, ensuring that the variables have default values of 0 if no counts are returned.
        $totalPresent = $attendanceCounts->totalPresent ?? 0;
        $totalAbsent = $attendanceCounts->totalAbsent ?? 0;
        $totalLeave = $attendanceCounts->totalLeave ?? 0;
        $totalLate = $violationsCounts->totalLate ?? 0;
        $totalIncompleteUniform = $violationsCounts->totalIncompleteUniform ?? 0;
        $totalLateIncompleteUniform = $violationsCounts->totalLateIncompleteUniform ?? 0;


        return view('dashboard', compact('totalStudents','totalPresent','totalAbsent','totalLeave','totalLate','totalIncompleteUniform','totalLateIncompleteUniform'));
    }
}
