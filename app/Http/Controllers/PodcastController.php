<?php

namespace App\Http\Controllers;

use App\Http\Transformers\PodcastTransformer;
use App\Podcast;
use App\Scopes\PodcastScope;
use Illuminate\Http\Request;

/**
 * Class PodcastController
 * @package App\Http\Controllers
 */
class PodcastController extends BaseController
{

    /** @var int  */
    const ITEMS_PER_PAGE = 12;

    /** @var string  */
    const QUERY_STATUS_PUBLISHED = 'published';
    /** @var string  */
    const QUERY_STATUS_REVIEW = 'review';

    /**
     * Display a list of Podcasts.
     *
     * @param string $status
     * @return \Illuminate\Http\Response
     */
    public function index(string $status)
    {
        if ($status === self::QUERY_STATUS_REVIEW) {
            $podcasts = Podcast::withoutGlobalScope(PodcastScope::class)
                ->where('status', Podcast::STATUS_REVIEW)
                ->paginate(self::ITEMS_PER_PAGE);
        } else {
            $podcasts = Podcast::paginate(self::ITEMS_PER_PAGE);
        }

        return $this->response->paginator($podcasts, new PodcastTransformer);
    }

    /**
     * Store a newly created podcast in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified podcast.
     *
     * @param  \App\Podcast  $podcast
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Podcast $podcast, int $id)
    {
        return $this->response->item($podcast->findOrFail($id), new PodcastTransformer);
    }

    /**
     * Update the specified podcast in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Podcast  $podcast
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Podcast $podcast)
    {
        //
    }

    /**
     * Remove the specified podcast from storage.
     *
     * @param  \App\Podcast  $podcast
     * @return \Illuminate\Http\Response
     */
    public function destroy(Podcast $podcast)
    {
        //
    }


}