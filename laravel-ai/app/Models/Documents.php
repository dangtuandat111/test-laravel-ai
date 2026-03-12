<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\Vector;

class Documents extends Model
{
    protected $fillable = ['title', 'content', 'embedding'];
    
    protected function casts(): array
    {
        return [
             'embedding' => 'array', 
        ];
    }
}