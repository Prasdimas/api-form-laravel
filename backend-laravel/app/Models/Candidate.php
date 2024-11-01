<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'name', 'email', 'phone', 'year', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
    public function skillSets()
    {
        return $this->hasMany(SkillSet::class, 'candidate_id');
    }
}
