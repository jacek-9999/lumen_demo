<?php
namespace App;
use Illuminate\Database\Eloquent\Model;


class Place extends model
{
    protected $fillable = ['lat', 'lng', 'name'];
}