@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tasks list</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                  <table class="table">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Due Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($search_results as $task)
                        <tr>
                          @foreach ($task->utrs as $utr)
                            @if($utr->user_id == Auth::id())
                              <td>{{$task->body}}</td>
                              <td>{{$task->due_date}}</td>
                              <td><a title="Edit task" class="btn btn-warning" href="{{route('home.edit', $task->id)}}"><i class="fas fa-pencil-alt"></i></span></a></td>
                              <td>

                                {!! Form::open(['method'=>'POST', 'action'=>['TasksController@leave']]) !!}
                                  {{ Form::hidden('utr_id', $task->id, ['class' => 'form-control', 'id'=>'id_receiver']) }}
                                  {!! Form::button('  <i class="fas fa-sign-out-alt"></i>',['type' => 'submit','class' => 'btn btn-success', 'title'=>'Leave task','onclick'=>'return confirm("Are you sure you want to leave this task?");']) !!}
                                {!! Form::close() !!}
                              </td>

                              <td>

                                {!! Form::open(['method'=>'DELETE', 'action'=>['TasksController@destroy', $task->id]]) !!}
                                  {!! Form::button('<i class="fas fa-trash"></i>',['type' => 'submit','class' => 'btn btn-danger','title'=>'Delete task','onclick'=>'return confirm("Are you sure you want to delete this task?");']) !!}
                                {!! Form::close() !!}
                              </td>
                        </tr>
                          @endif
                        @endforeach
                      @endforeach
                    </tbody>
                  </table>

                  <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal">Add Task</button>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Task</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">

            {!! Form::open(['method'=>'POST', 'action'=>'TasksController@store']) !!}
            <div class="form-group">
              {{ Form::label('body','Task description:') }}
              {{ Form::text('body', null, ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
              {{ Form::label('due_date','Due date:') }}
              {{ Form::date('due_date', \Carbon\Carbon::now()) }}
            </div>

            <h6>Assign this task to user (number of user tasks user currently have):</h6>

            <div class="form-group">
                @foreach($users as $user)
                  {{ Form::label($user->id, $user->name .' (current tasks: '. ($user->utrs->count()).')') }}
                      @if($user->id == Auth::id())
                        {{ Form::checkbox('users[]', $user->id, true)}}
                      @else
                        {{ Form::checkbox('users[]', $user->id)}}
                      @endif
                      <br />
                @endforeach
            </div>

              {!! Form::submit('Create task',['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
    </div>
  </div>
</div>
@endsection
