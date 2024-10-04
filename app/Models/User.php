<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Log;  // Log-Funktion hinzufügen

class User extends Authenticatable
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
            if (!$user->is_admin && !is_null($user->password)) {
                // Wenn is_admin false und Passwort gesetzt ist, Passwort löschen
                $user->password = null;
            }
            if (Auth::check() && !$user->is_admin && Auth::id() === $user->id) {
                Auth::logout();
                Session::invalidate();
            }
        });
        static::updated(function ($user) {
            // Überprüfen, ob der Admin-Status geändert wurde
            if ($user->wasChanged('is_admin') && !$user->is_admin) {
                //Log::info('Admin-Status entfernt für Benutzer: ' . $user->email);

                // Suche die Sessions des Benutzers in der Datenbank
                $sessions = DB::table('sessions')->where('user_id', $user->id)->get();

                foreach ($sessions as $session) {
                    DB::table('sessions')->where('id', $session->id)->delete();
                }

                if (Auth::id() === $user->id) {
                    Log::info('Benutzer wurde automatisch ausgeloggt: ' . $user->email);
                    Auth::logout();
                    Session::invalidate();
                }
            }
        });
    }
}
