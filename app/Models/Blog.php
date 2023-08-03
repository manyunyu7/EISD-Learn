<?php

namespace App\Models;


use App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Blog extends Model
{

  use HasFactory;
  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = [
    'image', 'title', 'content', 'user_id', 'category'
  ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
