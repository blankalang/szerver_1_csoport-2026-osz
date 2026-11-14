<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

//A PostResource osztály a Post modelhez tartozó adatokat alakítja át JSON formátumra, amikor az API válaszban visszaadjuk őket. A toArray metódusban definiáljuk, hogy mely mezőket szeretnénk visszaadni a JSON válaszban, és hogyan szeretnénk azokat formázni.
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //Segítség: https://laravel.com/framework/docs/13.x/eloquent-resources#main-content

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            // Az 'is_public' mezőt boolval() függvénnyel alakítjuk át logikai értékké, hogy a JSON válaszban true vagy false értéket kapjunk, ahelyett, hogy 0 vagy 1 lenne.
            'is_public' => boolval($this->is_public),
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
