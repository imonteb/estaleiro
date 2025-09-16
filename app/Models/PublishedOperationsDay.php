<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedOperationsDay extends Model
{
    protected $table = 'published_operations_day';
    protected $fillable = ['date'];
}
