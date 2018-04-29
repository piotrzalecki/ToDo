<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


  // Retriving rows from UserTaskRelation belonging to user
    public function utrs(){

      return $this->hasMany('App\UserTaskRelation');
    }

  // Retriving tasks beloging to user

    public function tasks(){

      return $this->hasManyThrough('App\Task','App\UserTaskRelation','user_id','id','id','task_id');
    }




  }
