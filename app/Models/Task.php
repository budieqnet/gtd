<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'title', 
        'description', 
        'priority', 
        'status', 
        'due_date', 
        'assigned_to', 
        'created_by'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Relasi: Task punya satu Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Task ditugaskan kepada Employee (bawahan)
     */
    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    /**
     * Relasi: Task dibuat oleh Employee (atasan/assigner)
     */
    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}
