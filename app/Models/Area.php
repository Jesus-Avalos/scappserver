<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = 'areas';
    protected $fillable = [
        'name',
        'general_objective',
        'contact',
        'status'
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function contests()
    {
        return $this->hasMany(Contest::class);
    }
}
