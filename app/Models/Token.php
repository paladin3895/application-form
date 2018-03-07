<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Function to generate a token
     *
     * @return string
     */
    protected static function generateToken()
    {
        return uniqid();
    }

    public function setEmailAttribute($value) {
        $this->attributes['email'] = $value;
        $this->attributes['token'] = static::generateToken();
        $this->attributes['expired_at'] = new \DateTime('now +' . config('app.token_expire_time') . ' hour');
    }
}
