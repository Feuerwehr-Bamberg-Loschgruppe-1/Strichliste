<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            if ($user->wasChanged('is_admin') && !$user->is_admin) {
                // Suche die Sessions des Benutzers (Benutzer B) in der Datenbank
                $sessions = DB::table('sessions')->where('user_id', $user->id)->get();

                foreach ($sessions as $session) {
                    DB::table('sessions')->where('id', $session->id)->delete(); // Lösche nur die Sessions von Benutzer B
                }
            }
        });
    }
}
