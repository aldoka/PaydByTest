<?php

namespace App\Http\Requests;

use App\Podcast;
use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class UpdatePodcast
 * @package App\Http\Requests
 */
class UpdatePodcast extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        // TODO define and implement regexp rules for 'name' and 'description ' fields. No ASCII and so on.
        $id = (int)$this->route('id');
        return [
            'name' => 'nullable|unique:podcasts,name,' . $id . '|min:4|max:128',
            'description' => 'nullable|min:4|max:1000',
            'marketing_url' => 'nullable|url|unique:podcasts,marketing_url,' . $id . '|max:128',
            'feed_url' => 'nullable|url|unique:podcasts,feed_url,' . $id . '|max:128',
            'image' => 'nullable|max:256',
            'status' => 'nullable|' . Rule::in(...Podcast::getAllStatuses()),
        ];
    }
}