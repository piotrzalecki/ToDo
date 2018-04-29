@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tasks list</div>

                <div class="card-body">
                  @if ($errors->any())
                      <div class="alert alert-danger">
                        <ul>
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                  @endif

                  {!! Form::model($task,['method'=>'PATCH', 'action'=>['TasksController@update', $task->id]]) !!}

                    <div class="form-group">
                        {{ Form::label('body','Task description:') }}
                        {{ Form::text('body', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('due_date','Due date:') }}
                        {{ Form::date('due_date', $task->due_date) }}
                    </div>

                    <h6>Assign this task to user (number of user tasks user currently have):</h6>

                    <div class="form-group">
                        @foreach($users as $user)
                              {{ Form::label($user->id, $user->name .' (current tasks: '. ($user->utrs->count()).')') }}

                                @if($utrs->where('user_id',$user->id)->count() != 0)
                                  {{ Form::checkbox('users[]', $user->id, true)}}
                                @else
                                  {{ Form::checkbox('users[]', $user->id)}}
                                @endif
                                <br />
                        @endforeach
                    </div>


                  {!! Form::submit('Update task',['class' => 'btn btn-primary']) !!}
                  {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
