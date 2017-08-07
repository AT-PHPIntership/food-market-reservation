<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyMenu extends Model
{
    use softDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function foods ()
    {
        return $this->hasMany(Food::class);
    }
}
