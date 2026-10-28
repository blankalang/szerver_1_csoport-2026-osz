<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

//A PostPolicy osztály a Post modelhez tartozó jogosultságokat határozza meg, jogosultságkezelő osztály. A Laravelben a policy-k segítségével lehet szabályozni, hogy egy adott felhasználó milyen műveleteket hajthat végre egy adott erőforráson (pl. model). A policy osztályban különböző metódusok találhatók, amelyek meghatározzák, hogy a felhasználó jogosult-e bizonyos műveletekre (pl. megtekintés, létrehozás, frissítés, törlés) az adott erőforráson.
class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // A create metódus ellenőrzi, hogy a felhasználó be van-e jelentkezve (nem null). Ha a felhasználó be van jelentkezve, akkor jogosult új Post model létrehozására. Az alapértetmezett érték false volt, de a kódot módosítottuk, hogy a bejelentkezett felhasználók létrehozhassanak új bejegyzéseket.
        return $user !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        //Ez a sor ellenőrzi, hogy a felhasználó adminisztrátor-e (is_admin tulajdonság true), vagy a poszt szerzője-e (author_id megegyezik a felhasználó id-jával). Ha bármelyik feltétel teljesül, akkor a felhasználó jogosult a poszt frissítésére. Ha egyik feltétel sem teljesül, akkor a felhasználó nem jogosult a poszt frissítésére.
     return $user -> is_admin || $post -> author_id === $user -> id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user -> is_admin || $post -> author_id === $user -> id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
