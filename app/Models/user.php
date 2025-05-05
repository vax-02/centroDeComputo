<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $table = 'users'; // si no se llama igual que el modelo
    protected $fillable = ['user', 'password', 'name', 'lastname', 'rol'];

    protected $hidden = ['created_at','updated_at','password'];
    public function subjects()
    {
        return $this->hasMany('App\Models\Subject', 'user_id');
    }
}
