@extends('layouts.master')
@section('title', 'Student Attendance')
@section('content')

    @if(session()->has('response'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session()->get('response') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

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
                      <form id="saveViolationsForm" action="saveViolations" method="post">
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
                                  <div class="input-group-text"><li class="fa fa-graduation-cap"></li></div>
                              </div>
                              <select name="violations" id="violations" class="form-control" required>
                                <option value="">Violation</option>
                                <option value="Late">Late</option>
                                <option value="Incomplete Uniform">Incomplete Uniform</option>
                                <option value="Late + Incomplete Uniform">Late + Incomplete Uniform</option>
                              </select>
                            </div>
                          </div>
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <div class="input-group-text"><li class="fa fa-graduation-cap"></li></div>
                              </div>
                              <input name="date" type="date" value="{{date('Y-m-d')}}" class="form-control" id="inlineFormInputGroup" required placeholder="Date">
                            </div>
                          </div>
                          <div class="col">
                            <input type="submit" class="btn btn-success" value="Save Record">
                          </div>
                        </div>
                      </form>
                      <div class="card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Roll</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th class="d-print-none">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < count($record); $i++)
                                <tr>
                                  <td>{{ $record[$i]->date }}</td>
                                  <td>{{ $record[$i]->rollNumber }}</td>
                                  <td>{{ $record[$i]->name}}<b> S/o </b>{{$record[$i]->father }}</td>
                                  <td>{{ $record[$i]->class."-".$record[$i]->section }}</td>
                                  <td>{{ $record[$i]->status }}</td>
                                  <td class="d-print-none">
                                      <a href={{"violations/delete/".$record[$i]->id}} class="student btn btn-danger btn-sm">
                                          <i class="fa fa-trash"></i>
                                      </a>
                                  </td>
                                </tr>
                            @endfor
                        </tbody>
                        </table>
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
<script src="{{asset('build/js/violations.js')}}"></script>
@stop
