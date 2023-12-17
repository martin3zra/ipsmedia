<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    public function resolveNext()
    {
        return Badge::where('id', '>', $this->id)->first();
    }
}
