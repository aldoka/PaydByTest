<?php
namespace App\Http\Transformers;

use App\Podcast;
use League\Fractal\TransformerAbstract;

class PodcastTransformer extends TransformerAbstract
{

    public function transform(Podcast $podcast)
    {
        // TODO add csrf protection, filter text before output
        return [
            'name' => $podcast->name,
            'description' => $podcast->description,
            'marketing_url' => $podcast->marketing_url,
            'feed_url' => $podcast->feed_url,
            'image' => $podcast->image,
            'status' => $podcast->status,
            'created_at' => optional($podcast->created_at)->format('Y-m-d H:i:s'),
            'deleted_at' => optional($podcast->deleted_at)->format('Y-m-d H:i:s'),
            ];
    }

}