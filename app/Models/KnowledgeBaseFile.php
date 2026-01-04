<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseFile extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
        'botpress_kb_id',
        'status',
    ];
}