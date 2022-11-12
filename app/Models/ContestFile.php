<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestFile extends Model
{
    use HasFactory;
    protected $table = 'contest_files';
    protected $fillable = [
        'contest_id',
        'title',
        'file_url',
        'status'
    ];
}
