<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Word extends Model
{
    use HasFactory;

    protected $table = 'words';

    protected $primaryKey = 'id';

    protected $fillable = ['course_id','polish','english'];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
