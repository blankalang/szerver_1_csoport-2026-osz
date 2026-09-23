<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//'is_admin'-t beírtam, mivel a User modelben is szerepel, és így a mass assignment protection miatt nem lehetne beállítani az értékét.
#[Fillable(['name', 'email', 'password','is_admin'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function posts(){
        // a hasMany kapcsolatot a User modelben is definiáljuk, hogy a felhasználóhoz tartozó posztokat lekérdezhessük.
        return $this->hasMany(Post::class, 'author_id');
    }

}
