<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
  protected $fillable = [

      'body',
      'due_date',
  ];


  // Retriving rows from UserTaskRelation table belonging to task
  public function utrs(){

    return $this->hasMany('App\UserTaskRelation');
  }




}
