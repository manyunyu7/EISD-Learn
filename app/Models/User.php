<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'id',
        'email',
        'password',
        'role',
        'profile_url',
        'contact',
        'institute',
        'jobs',
        'motto',
        'url_personal_website',
        'url_facebook',
        'url_instagram',
        'url_linkedin',
        'url_twitter',
        'url_whatsapp',
        'url_youtube',
    ];

        // Other model properties and methods

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'full_img_path',
    ];

    // Define an accessor method for full_img_path attribute
    public function getFullImgPathAttribute()
    {

        if(str_contains($this->profile_url,"profile-s3")){
            return "https://lms-modernland.s3.ap-southeast-3.amazonaws.com/".$this->profile_url;
        }
        // Assuming you have a column named 'img_filename' that stores the image filename
        // You can modify this to generate the full image path as per your requirement
        return asset('storage/profile/' . $this->profile_url);
    }
}
