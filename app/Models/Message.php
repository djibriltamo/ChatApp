<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'from_id', 'to_id', 'read_at', 'created_at'
    ];

    public $timestamps = false;

    protected $dates = ['created_at', 'read_at'];

    public function from ()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

}
