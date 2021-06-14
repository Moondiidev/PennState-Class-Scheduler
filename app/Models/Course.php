<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["name", "description", "credits", "type", "abbreviation", 'prerequisites', 'concurrents'];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'prerequisites' => 'array',
        'concurrents' => 'array',
    ];


    /**
     * Relationships
     */

    public function semesters()
    {
        return $this->belongsToMany(Semester::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
