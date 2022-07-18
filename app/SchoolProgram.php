<?php

namespace App;

use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SchoolProgram extends Model
{
    use HasFactory;

    protected $table = "school_program";

    

    public function scopeStatus($query)
    {
        return $query->where('sp_status', 1);
    }

    

}
