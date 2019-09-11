<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    protected $primaryKey = 'computer_id';

    public function operating_system(){
        return $this->hasOne("App\OperatingSystem", "id", "os_id");
    }

    public function files(){
        return $this->belongsToMany('App\File', 'computer_files', 'computer_id', 'file_id');
    }
}
