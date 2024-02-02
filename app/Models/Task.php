<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $guarded = []; 
    public function statusList(){
        return $this->hasMany(TaskStatus::class,'task_id');
    }

    public function user()
    {
    return $this->belongsTo(User::class,'assigned_user_id');
    }
}
