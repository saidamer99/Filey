<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create()
 * @method static whereBetween(string $string, array $array)
 */
class Log extends Model
{
    protected $fillable=['url','method','request_body','response'];
    use HasFactory;
}
