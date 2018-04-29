<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use Illuminate\Support\Facades\Auth;
use App\UserTaskRelation;
use App\User;
use App\Http\Requests\TaskRequest;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct(){

         $this->middleware('auth');
     }

    public function index(){


        $user = User::findOrFail(Auth::id());

        $users = User::all();

        return view('tasks.home',compact('users','user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request){


      // Storing new task and creating relations in UserTaskRelations
      // table for each user assigned to task

      $request = $request->all();

      $task_input = [
        'body' => $request['body'],
        'due_date' => $request['due_date'],
      ];

      $new_task = Task::create($task_input);

      foreach ($request['users'] as $user_id){
          $new_user_task_relation = [
              'user_id'=> $user_id,
              'task_id'=>$new_task->id,
          ];

          UserTaskRelation::create($new_user_task_relation);
        }

      session()->flash('message', 'Task added');
      return redirect('/home');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){

        $task =  Task::findOrFail($id);
        $users = User::all();
        $utrs = UserTaskRelation::where('task_id', $task->id)->get();

        return view('tasks.edit', compact('task','users','utrs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id){

      // Updating inforamtion about task and
      // recreating relatiosn i UTR table for assigned user

      $request = $request->all();

      $task_input = [
        'body' => $request['body'],
        'due_date' => $request['due_date'],
      ];

      $task = Task::findOrFail($id);
      $task->update($task_input);

      UserTaskRelation::where('task_id',$id)->delete();

      foreach ($request['users'] as $user_id){
        $new_user_task_relation = [
              'user_id'=> $user_id,
              'task_id'=>$task->id,
        ];
        UserTaskRelation::create($new_user_task_relation);
      }

      return redirect('/home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

      // Deleting task and all realated to it rows in UTR table

      Task::findOrFail($id)->delete();

      UserTaskRelation::where('task_id',$id)->delete();

      session()->flash('message', 'Task deleted');

      return redirect('/home');
    }


    public function leave(Request $request){


      // Leaving task by deleting row in UTR table realting to this task and user

      $request =  $request->all();
      $task_id = $request['utr_id'];

      $utr = UserTaskRelation::where('task_id',$task_id)->where('user_id', Auth::id());

      $utr->delete();


      return redirect('/home');
    }


    public function search(Request $request){

      // Searching function displaying result od separate page

        $request = $request->all();
        $search = $request['search_word'];

        if($search != ''){

          $search_results = Task::where('body','LIKE','%'.$search.'%')->get();
          $users = User::all();

          return view('tasks.search',compact('search_results','users'));

        } else {

          return redirect()->back();
        }
    }


    public function search_ajax(Request $request){


        // Serachng function for ajax requests

        $request = $request->all();
        $search = $request['search'];
        $output = '<ul>';

        if($search != ''){

          $search_results = Task::where('body','LIKE','%'.$search.'%')->get();

          foreach($search_results as $result){

            foreach($result->utrs as $utr){

              if($utr->user_id == Auth::id()){
                  $output .= '<li><a href="/home/search?search_word='.$result->body.'">'.$result->body.'</a></li>';
              }
            }

          }

          $output .='</ul>';
          return $output;
      }
  }

}
