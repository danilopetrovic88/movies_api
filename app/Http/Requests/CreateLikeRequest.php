<?php

namespace App\Http\Requests;

use App\Models\Like;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateLikeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $likeStates = array_values(Like::LIKE_STATES);
        
        return [
            'like' => [ 'string', Rule::in($likeStates)]
        ];
    }
}
