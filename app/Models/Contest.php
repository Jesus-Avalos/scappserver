<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;
    protected $table = 'contests';
    protected $fillable = [
        'area_id',
        'title',
        'file_url',
        'status'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function files()
    {
        return $this->hasMany(ContestFile::class);
    }
}
