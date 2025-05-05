<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subject extends Model
{
    //
    public function users(){
        return $this->belongsToMany(User::class, 'subjects_users', 'subject_id', 'user_id')
                ->withPivot('semestre');
    }
    
}
