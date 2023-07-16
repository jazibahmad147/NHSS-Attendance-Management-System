@extends('layouts.master')
@section('title', 'Attendance')
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
              <h2>Registered Classes</h2>
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
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th class="d-print-none">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($classes); $i++)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$classes[$i]->class}}</td>
                                        <td>{{$classes[$i]->section}}</td>
                                        <td class="d-print-none">
                                            <a href="{{ route('take-attendance', ['class' => $classes[$i]->class, 'section' => $classes[$i]->section]) }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus-circle"> Take Attendance</i>
                                            </a>
                                            <a href="{{ route('update-attendance', ['class' => $classes[$i]->class, 'section' => $classes[$i]->section]) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-pencil-square-o"> Update Attendance</i>
                                            </a>
                                            <a href="{{ route('view-attendance-register', ['class' => $classes[$i]->class, 'section' => $classes[$i]->section]) }}" class="btn btn-secondary btn-sm">
                                                <i class="fa fa-book"> Attendance Register</i>
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
@stop
