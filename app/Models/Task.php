<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'is_done' => 'boolean'
    ];

  
    protected $fillable = [
        'title',
        'is_done',
        'project_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'creator_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('creator',function(Builder $builder){
            $builder->where('creator_id', Auth::user()->id);
        });
    }
}
