<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ["name", "description", "credits", "type", "abbreviation", 'prerequisites', 'concurrents'];

    protected $casts = [
        'prerequisites' => 'array',
        'concurrents' => 'array',
    ];


    /**
     * Relationships
     */

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

}
