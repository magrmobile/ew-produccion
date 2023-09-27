<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;


class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'file_name',
        'excel_content',
        'json_content',
        'user_id',
        'generation_datetime'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
