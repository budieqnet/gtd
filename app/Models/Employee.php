<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nip',
        'name',
        'front_title',
        'back_title',
        'nik',
        'email',
        'password',
        'religion',
        'marital_status',
        'education',
        'address',
        'birth_place',
        'birth_date',
        'gender',
        'position',
        'position_id',
        'group_rank',
        'group_start_date',
        'position_start_date',
        'phone',
        'photo',
        'superior_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'superior_id' => 'integer',
            'is_admin' => 'boolean',
            'position_id' => 'integer',
            'birth_date' => 'date',
            'group_start_date' => 'date',
            'position_start_date' => 'date',
        ];
    }

    public function getFullNameAttribute(): string
    {
        $name = trim($this->front_title . ' ' . $this->name . ' ' . $this->back_title);
        return $name;
    }

    public function positionData()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function superior()
    {
        return $this->belongsTo(Position::class, 'superior_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'superior_id', 'position_id');
    }

    public function allSubordinates()
    {
        $position = $this->positionData;
        if (!$position) return collect();

        $descendantIds = $position->descendantIds();
        if (empty($descendantIds)) return collect();

        return Employee::with('positionData')
            ->whereIn('position_id', $descendantIds)
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->orderBy('positions.level')
            ->orderBy('positions.sort_order')
            ->orderBy('employees.name')
            ->select('employees.*')
            ->get();
    }

    public function allSuperiorIds(): array
    {
        $position = $this->positionData;
        if (!$position) return [];

        return $position->ancestorIds();
    }

    /**
     * Relasi: Tugas yang dibuat oleh Employee ini (sebagai atasan/assigner)
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Relasi: Tugas yang ditugaskan kepada Employee ini (sebagai bawahan/asigne)
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
}
