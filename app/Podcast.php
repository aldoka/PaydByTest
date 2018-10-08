<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Podcast
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $name
 * @property string $description
 * @property string $url
 * @property string|null $image
 * @property int $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Podcast whereUrl($value)
 * @mixin \Eloquent
 */
class Podcast extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Comment');
    }
}
