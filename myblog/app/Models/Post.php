<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// az alábbi sor hozzáadása szükséges, hogy a Post modelben is használni tudjuk a Fillable attribútumot
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// A Fillable attribútumot használjuk a mass assignment protection beállításához
#[Fillable(['title', 'content', 'is_public','author_id'])]

class Post extends Model
{
    // A HasFactory trait-et használjuk a Post modelben, hogy a factory-kat is használni tudjuk
    use HasFactory;
      public function author(){
        // A belongsTo kapcsolatot használjuk, hogy a Post modelben lekérdezhessük a hozzátartozó User modellt
        return $this -> belongsTo(User::class, 'author_id');
    }

    public function categories(){
        // A belongsToMany kapcsolatot használjuk, hogy a Post modelben lekérdezhessük a hozzátartozó Category modelleket
        return $this -> belongsToMany(Category::class)->withTimestamps();
    }

}
