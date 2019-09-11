<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['file_type', 'file_name', 'file_level'];

    public function computers(){
        return $this->belongsToMany('App\Computer', 'computer_files', 'file_id', 'computer_id');
    }

    public function type(){
        return $this->belongsTo('App\FileType', 'file_type');
    }
}
