<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $appends = ['avatar'];

    /**
     * Get the avatar URL for the user.
     */
    public function getAvatarAttribute()
    {
        $media = $this->getFirstMedia('avatars');
        return $media ? $media->getFullUrl() : null;
    }

    /**
     * Encrypt and store the name in the database.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt and return the name when retrieved.
     */
    public function getNameAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * Encrypt and store the email in the database.
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt and return the email when retrieved.
     */
    public function getEmailAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'media', //Hide the media relationship from the JSON response.
    ];

}
