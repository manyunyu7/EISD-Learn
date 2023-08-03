<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolio';
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
      'image', 'title', 'content','link', 'user_id', 'category'
    ];
  
    public function user()
    {

      return $this->belongsTo('App\User');
    }
}
