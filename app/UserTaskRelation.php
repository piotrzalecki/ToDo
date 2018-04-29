<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class UserTaskRelation extends Model
{
  protected $fillable = [

      'user_id',
      'task_id'

  ];

  // Retriving rows from Task table belonging to UTR
  // to receive inof about task

  public function task(){

    return $this->belongsTo('App\Task');
  }


}
