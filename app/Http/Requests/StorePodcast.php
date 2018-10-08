<?php

namespace App\Http\Requests;

use App\Podcast;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class StorePodcast
 * @package App\Http\Requests
 */
class StorePodcast extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // TODO define and implement regexp rules for 'name' and 'description ' fields. No ASCII and so on.
        return [
            'name' => 'required|min:4|max:128',
            'description' => 'required|min:4|max:1000',
            'marketing_url' => 'nullable|url|max:128',
            'feed_url' => 'required|url|max:128',
            'image' => 'nullable|max:256',
            'status' => 'nullable|' . Rule::in(...Podcast::getAllStatuses()),
        ];
    }
}