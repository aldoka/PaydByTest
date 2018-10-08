<?php

namespace App\Http\Controllers;

use App\Http\Transformers\PodcastTransformer;
use App\Podcast;
use App\Scopes\PodcastScope;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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


    const QUERY_STATUSES = [
        self::QUERY_STATUS_PUBLISHED,
        self::QUERY_STATUS_REVIEW,
    ];

    /**
     * Display a list of Podcasts.
     *
     * @param string $status
     * @return \Illuminate\Http\Response
     */
    public function index(string $status)
    {
        if (in_array($status, self::QUERY_STATUSES) === false) {
            throw new Dingo\Api\Exception\ResourceException('Unknown status filter');
        }

        try {
            switch($status) {
                case self::QUERY_STATUS_PUBLISHED:
                    $podcasts = Podcast::paginate(self::ITEMS_PER_PAGE);
                    break;
                case self::QUERY_STATUS_REVIEW:
                    $podcasts = Podcast::withoutGlobalScope(PodcastScope::class)
                        ->where('status', Podcast::STATUS_REVIEW)
                        ->paginate(self::ITEMS_PER_PAGE);
                    break;
            }
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\ResourceException('Application was unable process this podcasts list');
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
        $result = null;
        try{
            $result = $podcast->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Dingo\Api\Exception\ResourceException('Application can\'t find podcast with such id');
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\ResourceException('Application was unable process this podcast');
        }

        return $this->response->item($result, new PodcastTransformer);
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