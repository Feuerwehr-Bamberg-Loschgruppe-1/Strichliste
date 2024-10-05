<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'email',
        'password',
        'is_admin', // Admin-Status
    ];

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

    protected static function booted()
    {
        static::saving(function ($user) {
            // Wenn der Benutzer kein Admin ist und kein Passwort gesetzt hat, das Original-Passwort behalten
            if (!$user->is_admin && is_null($user->password)) {
                $user->password = $user->getOriginal('password');
            }

            // Wenn der Benutzer zum Admin gemacht wird und kein Passwort gesetzt ist, Passwort erforderlich machen
            if ($user->is_admin && is_null($user->password)) {
                throw new \Exception('Admin-Benutzer müssen ein Passwort haben.');
            }
        });

        static::updated(function ($user) {
            // Prüfe, ob der is_admin-Status geändert wurde und der Benutzer kein Admin mehr ist
            if ($user->wasChanged('is_admin') && !$user->is_admin) {
                // Suche alle aktiven Sessions des Benutzers in der Datenbank
                $sessions = DB::table('sessions')->where('user_id', $user->id)->get();

                foreach ($sessions as $session) {
                    // Lösche jede Session des Benutzers
                    DB::table('sessions')->where('id', $session->id)->delete();
                }
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    public function getFilamentName(): string
    {
        return "{$this->name} {$this->first_name}";
    }
}
