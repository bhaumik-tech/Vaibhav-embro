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
        'page_permissions',
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
            'page_permissions' => 'array',
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

    public function hasPagePermission($page, $action, $firmId = null)
    {
        if ($this->isAdmin()) {
            return true;
        }

        $perms = $this->page_permissions ?? [];

        if ($firmId) {
            // Check specific firm
            if (isset($perms[$firmId][$page])) {
                if ($action === 'any' && !empty($perms[$firmId][$page])) return true;
                if (in_array($action, (array) $perms[$firmId][$page])) return true;
            }
            
            // If they don't have it for this specific firm, check if it's in the 'global' fallback
            if (isset($perms['global'][$page])) {
                if ($action === 'any' && !empty($perms['global'][$page])) return true;
                if (in_array($action, (array) $perms['global'][$page])) return true;
            }
        }

        // Check if ANY firm (or global, or old format) has this permission (for sidebar visibility)
        foreach ($perms as $key => $value) {
            if (is_numeric($key) || $key === 'global') {
                // New format: $value is an array of pages
                if (is_array($value) && isset($value[$page])) {
                    if ($action === 'any' && !empty($value[$page])) return true;
                    if (in_array($action, (array) $value[$page])) return true;
                }
            } else {
                // Old format: $key is the page name itself
                if ($key === $page) {
                    if ($action === 'any' && !empty($value)) return true;
                    if (in_array($action, (array) $value)) return true;
                }
            }
        }

        return false;
    }
}
