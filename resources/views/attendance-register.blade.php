@extends('layouts.master')
@section('title', 'Attendance Register')
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
            <form id="" action="{{ route('view-attendance-register', ['class' => $class, 'section' => $section]) }}" method="POST">
                @csrf
                <div class="x_title">
                <h2>Attendance Register | {{$class}} {{$section}}</h2>
                <div class="row nav navbar-right panel_toolbox d-print-none">
                    <div class="col"><input type="month" id="date" name="date" value="{{date('Y-m')}}" class="d-inline form-control form-control-sm" required></div>
                    <div class="col"><input type="submit" id="updateBtn" class="d-inline btn btn-sm btn-success" value="Fetch"></div>
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
                                            <th>Roll Number</th>
                                            <th>Name</th>
                                            <th>Father</th>
                                            @for ($i = 0; $i < count($dates); $i++)
                                                <th>{{$dates[$i]}}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($rollNumbers); $i++)
                                            <tr>
                                                <td>{{$records[$i]->rollNumber}}</td>
                                                <td>{{$records[$i]->name}}</td>
                                                <td>{{$records[$i]->father}}</td>
                                                @foreach ($records as $record)
                                                    @if ($record->rollNumber == $rollNumbers[$i])
                                                        <td>{{ $record->attendanceStatus }}</td>
                                                    @endif
                                                @endforeach
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
