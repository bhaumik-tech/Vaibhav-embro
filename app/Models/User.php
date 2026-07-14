<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'primary_firm_name',
        'mobile_no',
        'post',
        'second_mobile_no',
        'username',
        'permission',
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

    public function getPermittedFirmIds()
    {
        if (empty($this->permission)) {
            return [];
        }
        $perms = explode(',', $this->permission);
        if (in_array('admin', $perms)) {
            return \App\Models\Firm::pluck('id')->toArray();
        }
        return $perms;
    }

    public function isAdmin()
    {
        if (empty($this->permission)) return false;
        return in_array('admin', explode(',', $this->permission));
    }

    public function getPermissionNames()
    {
        if (empty($this->permission)) {
            return [];
        }
        
        $perms = explode(',', $this->permission);
        if (in_array('admin', $perms)) {
            return ['Full Admin (All Firms)'];
        }

        return \App\Models\Firm::whereIn('id', $perms)->pluck('name')->toArray();
    }
}
