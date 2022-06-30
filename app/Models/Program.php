<?php

 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Program extends Model
{

     

    protected $primaryKey = 'p_id';
    protected $table = 'programs';

    protected $fillable = [
        "p_id",
        "p_name",        
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
