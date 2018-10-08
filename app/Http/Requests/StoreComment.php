<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class StoreComment extends FormRequest
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
        // TODO define and implement regexp rules for 'author_name' and 'comment' fields. No ASCII and so on.
        $name = $this->post('author_name');
        $email = $this->post('author_email');
        $comment = $this->post('comment');

        $unique = Rule::unique('comments')
            ->where(
                function ($query) use ($name, $email, $comment) {
                    $commentSubstr = substr($comment, 0, 191);

                    return $query
                        ->whereAuthorName($name)
                        ->whereAuthorEmail($email)
                        ->whereComment($comment)
                        ->whereCommentSubstr($commentSubstr);
                }
            );

        return [
            'author_name' => 'required|string|min:4|max:64',
            'author_email' => 'required|email|min:6|max:64',
            'comment' => [
                $unique,
                'required',
                'max:1000',
            ],
        ];
    }
}