<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
    use HasFactory;

    protected $table = 'showcases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'project_name',
        'description',
        'tech_stack',
        'thumbnail_path',
        'image_paths',
        'project_type',
        'project_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tech_stack' => 'array',
    ];

    /**
     * Get the user that owns the showcase.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
