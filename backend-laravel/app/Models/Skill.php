<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    protected $dates = ['created_by', 'updated_by', 'deleted_by'];

    // Define relationships
    public function skillSets()
    {
        return $this->hasMany(SkillSet::class, 'skill_id');
    }
}
