<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $table = 'users'; // si no se llama igual que el modelo
    protected $fillable = ['user', 'password', 'name', 'lastname', 'rol'];

    public function subjects()
    {
        return $this->hasMany('App\Models\Subject', 'user_id');
    }
}
