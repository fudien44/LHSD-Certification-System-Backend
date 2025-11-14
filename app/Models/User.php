<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\EmpMain;
use App\Models\PassSlip;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $guarded = array();

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function info() {
        return $this->HasOne(EmpMain::class, 'id', 'emp_id');
    }
    
    public function profile() {
        return $this->HasOne(ProfilePic::class, 'user_id', 'id');
    }

    public function esign() {
        return $this->HasOne(Signature::class, 'user_id', 'id');
    }

    public function mypds() {
        return $this->hasOne(EmpMain::class, 'id', 'emp_id');
    }
    public function passslip() {
        return $this->hasMany(PassSlip::class, 'user_id', 'id');
    }
    
}
