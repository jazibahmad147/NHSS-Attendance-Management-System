@extends('layouts.master')
@section('title', 'Student Attendance')
@section('content')

    <div class="row">
        <div class="col">
          <div class="x_panel tile">
            <div class="x_title">
            <h2>Search Student Attendance</h2>
            <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card-box">
                      <form id="" action="search-student-attendance" class="d-print-none" method="post">
                        @csrf
                        <div class="row m-auto">
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <div class="input-group-text"><li class="fa fa-user"></li></div>
                              </div>
                              <input name="rollNumber" type="number" id="rollNumber" class="form-control" id="inlineFormInputGroup" required placeholder="Student Roll Number">
                            </div>
                          </div>
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <div class="input-group-text"><li class="fa fa-graduation-cap"></li></div>
                              </div>
                              <select name="class" id="class" class="form-control" required>
                                <option value="">Class</option>
                                @foreach ($classes as $class)
                                  <option value="{{ $class }}">{{ $class }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <div class="input-group-text"><li class="fa fa-graduation-cap"></li></div>
                              </div>
                              <select name="section" id="section" class="form-control" required>
                                <option value="">Section</option>
                                @foreach ($sections as $section)
                                  <option value="{{ $section }}">{{ $section }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <div class="input-group-text"><li class="fa fa-calendar"></li></div>
                              </div>
                              <input name="startDate" type="date" class="form-control" id="inlineFormInputGroup" required placeholder="Start Date">
                            </div>
                          </div>
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <div class="input-group-text"><li class="fa fa-calendar"></li></div>
                              </div>
                              <input name="endDate" type="date" value="{{date('Y-m-d')}}" class="form-control" id="inlineFormInputGroup" required placeholder="Start Date">
                            </div>
                          </div>
                          <div class="col">
                            <input type="submit" class="btn btn-success" value="Fetch Attendance">
                          </div>
                        </div>
                      </form>
                      <div class="card-box table-responsive">
                        @if (isset($attendanceRecord))
                          <table class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                  <tr>
                                    <th>Roll Number</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Total Absents</th>
                                    <th>Total Leaves</th>
                                    <th>Total Late Comes</th>
                                    <th>Total Incomplete Uniform</th>
                                    <th>Total Late + Incomplete Uniform</th>
                                  </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>{{ $attendanceRecord->rollNumber }}</td>
                                  <td>{{ $attendanceRecord->name }}<b> S/o </b>{{$attendanceRecord->father}}</td>
                                  <td>{{ $attendanceRecord->class."-".$attendanceRecord->section }}</td>
                                  <td>{{ $attendanceRecord->total_absents }}</td>
                                  <td>{{ $attendanceRecord->total_leaves }}</td>
                                  <td>{{ $violationRecord->total_late }}</td>
                                  <td>{{ $violationRecord->total_incomplete_uniform }}</td>
                                  <td>{{ $violationRecord->total_late_incomplete_uniform }}</td>
                                </tr>
                              </tbody>
                          </table>
                          <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                              <tr>
                                <th colspan="5" class="text-center">Dates</th>
                              </tr>
                              <tr>
                                <th>Absent</th>
                                <th>Leave</th>
                                <th>Late</th>
                                <th>Incomplete Uniform</th>
                                <th>Late + Incomplete Uniform</th>
                              </tr>
                            </thead>
                            <tbody>
                              <td>
                                @if ($attendanceRecord->absent_dates)
                                    @foreach (explode(',', $attendanceRecord->absent_dates) as $absentDate)
                                        {{ $absentDate }}<br>
                                    @endforeach
                                @else
                                    No Absent Dates
                                @endif
                              </td>
                              <td>
                                @if ($attendanceRecord->leave_dates)
                                    @foreach (explode(',', $attendanceRecord->leave_dates) as $leaveDate)
                                        {{ $leaveDate }}<br>
                                    @endforeach
                                @else
                                    No Leave Dates
                                @endif
                              </td>
                              <td>
                                @if ($violationRecord->late_dates)
                                    @foreach (explode(',', $violationRecord->late_dates) as $lateDate)
                                        {{ $lateDate }}<br>
                                    @endforeach
                                @else
                                    No Late Dates
                                @endif
                              </td>
                              <td>
                                @if ($violationRecord->incomplete_uniform_dates)
                                    @foreach (explode(',', $violationRecord->incomplete_uniform_dates) as $incompleteUniformDate)
                                        {{ $incompleteUniformDate }}<br>
                                    @endforeach
                                @else
                                    No Incomplete Uniform	 Dates
                                @endif
                              </td>
                              <td>
                                @if ($violationRecord->late_incomplete_uniform_dates)
                                    @foreach (explode(',', $violationRecord->late_incomplete_uniform_dates) as $lateIncompleteUniformDate)
                                        {{ $lateIncompleteUniformDate }}<br>
                                    @endforeach
                                @else
                                    No 	Late + Incomplete Uniform Dates
                                @endif
                              </td>
                            </tbody>
                          </table>
                        @else
                          <p>No data available.</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
    </div>
@stop
@section('footer-link')
<script src="{{asset('build/js/attendance-view.js')}}"></script>
@stop
