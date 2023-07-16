@extends('layouts.master')
@section('title', 'Students')
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
        <div class="col-md-4 col-sm-4 d-print-none">
          <div class="x_panel tile">
            <div class="x_title">
              <h2>Add Student Record</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="student" class="form-label-left input_mask" action="addStudent" method="POST">
                    @csrf
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-sort-numeric-desc"></li></div>
                        </div>
                        <input name="id" type="hidden" id="id">
                        <input name="rollNumber" type="number" id="rollNumber" class="form-control" id="inlineFormInputGroup" required autofocus placeholder="Roll Number">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-user"></li></div>
                        </div>
                        <input name="name" type="text" id="name" class="form-control" id="inlineFormInputGroup" required placeholder="Student Name">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-user"></li></div>
                        </div>
                        <input name="father" type="text" id="father" class="form-control" id="inlineFormInputGroup" required placeholder="Student's Father Name">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-graduation-cap"></li></div>
                        </div>
                        <input name="class" type="text" id="class" class="form-control" id="inlineFormInputGroup" required placeholder="Class">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><li class="fa fa-graduation-cap"></li></div>
                        </div>
                        <input name="section" type="text" id="section" class="form-control" id="inlineFormInputGroup" required placeholder="Section">
                    </div>
                    <div class="row">
                        <div class="col-md-9"><button type="submit" id="btn" class="btn btn-success w-100">Add Student Record</button></div>
                        <div class="col-md-3"><button onclick="window.location.reload();" class="btn btn-warning w-100"><li class="fa fa-undo"></li></button></div>
                    </div>
                </form>
            </div>
          </div>
        </div>
        
        <div class="col-md-8 col-sm-8">
          <div class="x_panel tile">
            <div class="x_title">
              <h2>Manage Student Records</h2>
              <div class="row nav navbar-right panel_toolbox">
                <form action="students" class="d-print-none" method="POST">
                    @csrf
                    <div class="col-md-4">
                        <select class="d-inline form-control form-control-sm" name="class" required>
                            @foreach ($classes as $class)
                                <option value="{{$class}}">{{$class}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="d-inline form-control form-control-sm" name="section" required>
                            @foreach ($sections as $section)
                                <option value="{{$section}}">{{$section}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input type="submit" class="d-inline btn btn-sm btn-primary" value="Filter"></div>
                </form>
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
                                    <th>Roll</th>
                                    <th>Name</th>
                                    <th>Father</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th class="d-print-none">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($students); $i++)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{ $students[$i]->rollNumber }}</td>
                                        <td>{{ $students[$i]->name }}</td>
                                        <td>{{ $students[$i]->father }}</td>
                                        <td>{{ $students[$i]->class }}</td>
                                        <td>{{ $students[$i]->section }}</td>
                                        <td class="d-print-none">
                                            <button onclick="editStudent({{$students[$i]->id}})" class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href={{"student/delete/".$students[$i]->id}} class="student btn btn-danger btn-sm">
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
@stop
@section('footer-link')
<script src="{{asset('build/js/student.js')}}"></script>
@stop
