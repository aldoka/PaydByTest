<?php
namespace App\Http\Transformers;

use App\Podcast;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;

class PodcastTransformer extends TransformerAbstract
{

    public function transform(Podcast $podcast)
    {
        // TODO add csrf protection, filter text before output
        $image = $podcast->image;
        if (empty($image) === false) {
            if (strpos($podcast->image, 'http') !== 0) {
                $image = URL::to('/') . Storage::url($podcast->image);
            }
        }

        return [
            'id' => $podcast->id,
            'name' => $podcast->name,
            'description' => $podcast->description,
            'marketing_url' => $podcast->marketing_url,
            'feed_url' => $podcast->feed_url,
            'image' => $image,
            'status' => $podcast->status,
            'created_at' => optional($podcast->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($podcast->updated_at)->format('Y-m-d H:i:s'),
            'comments' => $podcast->comments,
            ];
    }

}