<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostStoreOrUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Alapértelmezésben false a authorize() metódus, ezért vissza kell adni true-t, hogy a validáció lefusson, mert false setén automatikusan elutasításra kerül.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // A validációs szabályokat a rules() metódusban adjuk meg. A title mező kötelező, string típusú. A content mező kötelező, string típusú, minimum 10 karakter hosszú. Az author_id mező kötelező, integer típusú, és léteznie kell a users táblában. A categories mező opcionális, tömb típusú. A categories.* mező opcionális, integer típusú, és léteznie kell a categories táblában.
        return [
           'title' => 'required|string',
            'content' => 'required|string|min:10',
            'author_id' => 'required|integer|exists:users,id',
            'categories' => 'array',
            'categories.*' => 'integer|distinct|exists:categories,id'
        ];
    }


    public function messages(){
        // A messages() metódusban megadhatjuk a validációs hibákhoz tartozó egyedi üzeneteket. Ha a content mező nem éri el a minimum 10 karaktert, akkor a megadott üzenetet jelenítjük meg.

   return [
            'content.min' => 'A tartalom legalább 10 karakter kell legyen!'
        ];
    }
}
