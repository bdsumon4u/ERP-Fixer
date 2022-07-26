<?php

namespace App\Http\Requests;

use App\Models\Taxonomy;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaxonomyRequest extends FormRequest
{
    protected string $type;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:35', $this->unique()],
            'slug' => ['required', 'max:35', $this->unique()],
        ];
    }

    public function unique()
    {
        return function ($attribute, $value, $fail) {
            Taxonomy::query()
                ->where('type', $this->type)
                ->where($attribute, $value)
                ->firstOr(function () use ($attribute, $value, $fail) {
                    $fail('The '.$attribute.' has already been taken.');
                });
        };
    }
}
