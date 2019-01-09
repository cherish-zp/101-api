<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;

class CoinDynamicFreed extends Model
{
    use Uuids;

    protected $table = 'coin_dynamic_freed';

    protected $guarded = [];
}
