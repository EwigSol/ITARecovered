<?php

 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Province extends Model
{

     

    protected $primaryKey = 'p_id';
    protected $table = 'provinces';

    protected $fillable = [
        "p_id",
        "p_name",        
        "p_status",

    ];

    protected $hidden = [

    ];

    protected $dates = [
        "created_at",
        "updated_at",

    ];

  




}
