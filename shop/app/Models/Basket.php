<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Basket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'baskets';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function good() {
        return $this->belongsTo(Good::class);
    }
}
