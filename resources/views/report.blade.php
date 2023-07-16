@extends('layouts.master')
@section('title', 'Attendnace Report')
@section('content')

    <div class="row">
        <div class="col">
          <div class="x_panel tile">
            <div class="x_title">
            <h2>Attendance Report</h2>
            <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card-box">
                      <form id="" action="report" class="d-print-none" method="post">
                        @csrf
                        <div class="row m-auto">
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
                            <input type="submit" class="btn btn-success" value="Fetch Report">
                          </div>
                        </div>
                      </form>
                      <div class="card-box table-responsive">
                        @if (isset($students))
                          <table class="table table-striped table-bordered" id="reportDatatable" style="width:100%">
                              <thead>
                                  <tr>
                                    <th>Roll Number</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Presents</th>
                                    <th>Absents</th>
                                    <th>Leaves</th>
                                    <th>Late Comes</th>
                                    <th>Incomplete Uniform</th>
                                    <th>Late + Incomplete Uniform</th>
                                  </tr>
                              </thead>
                              <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $student->rollNumber }}</td>
                                        <td>{{ $student->name }}<b> S/o </b>{{$student->father}}</td>
                                        <td>{{ $student->class."-".$student->section }}</td>
                                        <td>{{ $student->total_presents }}</td>
                                        <td>{{ $student->total_absents }}</td>
                                        <td>{{ $student->total_leaves }}</td>
                                        <td>{{ $student->total_late }}</td>
                                        <td>{{ $student->total_incomplete_uniform }}</td>
                                        <td>{{ $student->total_late_incomplete_uniform }}</td>
                                    </tr>
                                @endforeach
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
<script>
    $(document).ready(function() {
    $('#reportDatatable').DataTable({
        pageLength: 40,
        });
    });
</script>
@stop