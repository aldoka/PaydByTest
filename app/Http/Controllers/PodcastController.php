<?php

namespace App\Http\Controllers;

use App\Http\Transformers\PodcastTransformer;
use App\Podcast;
use Illuminate\Http\Request;

/**
 * Class PodcastController
 * @package App\Http\Controllers
 */
class PodcastController extends BaseController
{

    /** @var int  */
    const ITEMS_PER_PAGE = 12;

    /**
     * Display a list of Podcasts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $podcasts = Podcast::paginate(self::ITEMS_PER_PAGE);
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