<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseAttachment extends Model
{
    use HasFactory;
    protected $table = 'response_attachments'; // opsional, jika nama tabel tidak mengikuti konvensi plural
    protected $fillable = ['path'];

    public function response()
    {
        return $this->belongsTo(Response::class);
    }
}
