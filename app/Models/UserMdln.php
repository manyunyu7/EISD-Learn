<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMdln extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'ithub';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users'; // Assuming the table name is 'users' in the 'ithub' database

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'refresh_token',
        'remember_token',
    ];


}
