<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id', 'skill_id',
    ];

    // Define relationships
    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id')->select(['id', 'name', 'email']); // Adjust fields accordingly
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id')->select(['id', 'name']); // Adjust fields accordingly
    }
}
