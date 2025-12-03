<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<<< HEAD:.history/app/Models/SeguimientoClientesImagenes_20240907005240.php
class SeguimientoClientesImagenes extends Model
{
    use HasFactory;
========
class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name','address','phone','taxpayer_id'];
    

>>>>>>>> 315cc16c0b22309447497a0584b4df3ab55431d3:app/Models/Company.php
}
