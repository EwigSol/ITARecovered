<?php

 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class District extends Model
{

     

    protected $primaryKey = 'district_id';
    protected $table = 'districts';

    protected $fillable = [
        "district_id",
        "district_name",        
        "status",
        "created_at",
        "updated_at",

    ];

    protected $hidden = [

    ];

    protected $dates = [
        "created_at",
        "updated_at",

    ];

  




}
