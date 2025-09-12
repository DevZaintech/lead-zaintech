<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'ID_USER';
    public $timestamps = false; // karena CREATED_AT dan UPDATED_AT beda
    protected $fillable = ['NAMA','EMAIL','PASSWORD','ROLE','CREATED_AT','UPDATED_AT','DELETED_AT'];
    
    // protected $hidden = ['PASSWORD'];

    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }
}
