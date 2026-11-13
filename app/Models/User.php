<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    /**
     * True bila role user punya salah satu kunci akses yang diminta
     * (peta akses ada di config/roles.php; '*' = semua akses).
     */
    public function hasAccess(string ...$permissions): bool
    {
        $allowed = config("roles.permissions.{$this->role}", []);

        if (in_array('*', $allowed, true)) {
            return true;
        }

        return count(array_intersect($permissions, $allowed)) > 0;
    }

    public function roleLabel(): string
    {
        return config("roles.labels.{$this->role}", ucfirst((string) $this->role));
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isPanitiaPpdb(): bool
    {
        return in_array($this->role, ['superadmin', 'panitia_ppdb']);
    }

    public function isAdminPerpus(): bool
    {
        return in_array($this->role, ['superadmin', 'admin_perpus']);
    }

    public function isHumas(): bool
    {
        return in_array($this->role, ['superadmin', 'humas']);
    }
}