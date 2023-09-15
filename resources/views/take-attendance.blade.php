@extends('layouts.master')
@section('title', 'Take Attendance')
@section('content')

    <div class="row">
        <div class="col">
          <div class="x_panel tile">
            <form id="saveAttendanceForm" action="/saveAttendance" method="POST">
                @csrf
                <div class="x_title">
                <h2>Take Attendance | {{$class}} {{$section}}</h2>
                <div class="row nav navbar-right panel_toolbox">
                    <div class="col"><input type="date" id="date" name="date" value="{{date('Y-m-d')}}" class="d-inline form-control form-control-sm" required></div>
                    <div class="col"><input type="submit" class="d-inline btn btn-sm btn-success" value="Save"></div>
                </div>
                <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Roll Number</th>
                                            <th>Name</th>
                                            <th>Father</th>
                                            <th colspan="3" class="text-center">Attendance</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"></th>
                                            <th>P</th>
                                            <th>A</th>
                                            <th>L</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($records); $i++)
                                            <input class="d-none" type="text" id="id{{$records[$i]->id}}" name="studentId[]" value="{{$records[$i]->id}}">
                                            <tr>
                                                <td>{{$records[$i]->rollNumber}}</td>
                                                <td>{{$records[$i]->name}}</td>
                                                <td>{{$records[$i]->father}}</td>
                                                <td><input type="radio" value="P" name="attendanceStatus{{$records[$i]->id}}" checked required></td>
                                                <td><input type="radio" value="A" name="attendanceStatus{{$records[$i]->id}}" required></td>
                                                <td><input type="radio" value="L" name="attendanceStatus{{$records[$i]->id}}" required></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </div>
    </div>
@stop
@section('footer-link')
<script src="{{asset('build/js/attendance.js')}}"></script>
@stop
