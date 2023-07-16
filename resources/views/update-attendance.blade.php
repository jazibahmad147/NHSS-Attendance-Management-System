@extends('layouts.master')
@section('title', 'Update Attendance')
@section('content')

    <div class="row">
        <div class="col">
          <div class="x_panel tile">
            <form id="saveAttendanceForm" action="/updateAttendance" method="POST">
                @csrf
                <div class="x_title">
                <h2>Update Attendance</h2>
                <div class="row nav navbar-right panel_toolbox">
                    <div class="col"><input type="date" id="date" name="date" value="{{date('Y-m-d')}}" class="d-inline form-control form-control-sm" required></div>
                    <div class="col"><input type="button" onclick="fetchAttendance()" class="d-inline btn btn-sm btn-info" value="Fetch"></div>
                    <div class="col"><input type="submit" id="updateBtn" class="d-inline btn btn-sm btn-success" value="Update" disabled></div>
                    <input type="hidden" id="class" name="class" value="{{$class}}">
                    <input type="hidden" id="section" name="section" value="{{$section}}">
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
                                            <th>No.</th>
                                            <th>Roll Number</th>
                                            <th>Name</th>
                                            <th>Father</th>
                                            <th colspan="3" class="text-center">Attendance</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th>P</th>
                                            <th>A</th>
                                            <th>L</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody"></tbody>
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
