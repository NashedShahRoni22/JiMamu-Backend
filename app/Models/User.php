<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    static $status = ['pending'=> 1, 'active' => 2, 'inactive' => 3];
    static $statusName = [1 => 'pending', 2 => 'active', 3=>'inactive'];
    static $userType = ['user' => 1, 'rider' => 2];
    protected $fillable = [
        'name',
        'email',
        'password',
        'verification_code',
        'profile_image',
        'phone_number',
        'dob',
        'gender',
        'user_type',
        'status',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profileImage() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset('storage/').'/'.$value : null
            );
    }
    public function userRiders(){
        return $this->hasMany(UserRider::class, 'user_id', 'id');
    }
    public function riderBankInformations()
    {
        return $this->hasMany(RiderBankInformation::class, 'user_id', 'id');
    }
    public function userBankInformations(){
        return $this->hasMany(UserBankInformation::class, 'user_id', 'id');
    }
    public function riderCancelFlags(){
        return $this->hasMany(RiderCancelFlag::class, 'rider_id', 'id');
    }
}
