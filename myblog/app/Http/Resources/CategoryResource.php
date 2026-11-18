<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

//A CategoryResource osztály a Category modelhez tartozó adatokat alakítja át JSON formátumra, amikor az API válaszban visszaadjuk őket. A toArray metódusban definiáljuk, hogy mely mezőket szeretnénk visszaadni a JSON válaszban, és hogyan szeretnénk azokat formázni.
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "color" => $this->color
        ];
    }
}
