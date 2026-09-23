<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// A Fillable attribútumot használjuk a mass assignment protection beállításához
  #[Fillable(['name', 'color'])]

class Category extends Model
{
    use HasFactory;

  public function posts(){
    // A belongsToMany kapcsolatot használjuk, hogy a Category modelben lekérdezhessük a hozzátartozó Post modelleket. A withTimestamps() metódus biztosítja, hogy a pivot táblában a created_at és updated_at mezők automatikusan frissüljenek.
        return $this -> belongsToMany(Post::class)->withTimestamps();
    }
}
