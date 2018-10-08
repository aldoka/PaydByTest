<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Comment
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $author_name
 * @property string $author_email
 * @property string $comment
 * @property string $comment_substr
 * @property int $podcast_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereAuthorEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCommentSubstr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment wherePodcastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{

    use SoftDeletes;

    /** @var bool  */
    public $timestamps = true;

    /** @var array */
    protected $fillable = ['podcast_id', 'author_name', 'author_email', 'comment', 'comment_substr', 'created_at', 'updated_at', 'deleted_at'];
    /** @var array  */
    protected $hidden = ['deleted_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function podcast() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Podcast');
    }


}
