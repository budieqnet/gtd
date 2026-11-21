<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['name', 'description', 'level', 'sort_order'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function parents()
    {
        return $this->belongsToMany(Position::class, 'position_parent', 'position_id', 'parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(Position::class, 'position_parent', 'parent_id', 'position_id');
    }

    public function descendantIds(): array
    {
        $ids = [];
        $children = $this->children()->get();
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->descendantIds());
        }
        return $ids;
    }

    public function ancestorIds(): array
    {
        $ids = [];
        $parents = $this->parents()->get();
        foreach ($parents as $parent) {
            $ids[] = $parent->id;
            $ids = array_merge($ids, $parent->ancestorIds());
        }
        return $ids;
    }
}
