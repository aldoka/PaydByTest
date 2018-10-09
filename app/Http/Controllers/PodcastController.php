<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePodcast;
use App\Http\Requests\UpdatePodcast;
use App\Http\Transformers\PodcastTransformer;
use App\Podcast;
use App\Scopes\PodcastScope;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            return $this->response->errorBadRequest('Unknown status filter');
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
        } catch (\Throwable $t) {
            return $this->response->errorInternal('Application was unable to save this podcast');
        }

        return $this->response->paginator($podcasts, new PodcastTransformer);
    }

    /**
     * Store a newly created podcast in storage.
     *
     * @param  StorePodcast $request
     * @param Podcast podcast
     * @return \Illuminate\Http\Response
     */
    public function store(StorePodcast $request, Podcast $podcast, Storage $storage)
    {
        try {
            $validated = $request->validated();
            $validated['status'] = Podcast::STATUS_REVIEW;

            $image = $request->file('image');
            if (empty($image) === false) {
                $fileName = Str::random(10) . '.' . substr($image->getMimeType(), strlen('image/'));
                $path = $request->file('image')->storePubliclyAs('public', $fileName);
                $validated['image'] = $fileName;
            }

            $podcast->fill($validated);
            $podcast->save();
        } catch (\Throwable $t) {
            return $this->response->errorInternal('Application was unable to save this podcast');
        }

        return $this->response->created();
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
        } catch (\Throwable $t) {
            return $this->response->errorInternal('Application was unable to show this podcast');
        }

        return $this->response->item($result, new PodcastTransformer);
    }

    /**
     * Update the specified podcast in storage.
     *
     * @param  UpdatePodcast $request
     * @param  \App\Podcast  $podcast
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePodcast $request, Podcast $podcast, int $id)
    {
        try {
            $result = $podcast->findOrFail($id)
                ->fill($request->validated())
                ->save();
        } catch (ModelNotFoundException $e) {
            return $this->response->errorNotFound('Application can\'t find podcast with such id');
        } catch (\Throwable $t) {
            return $this->response->errorInternal('Application was unable to save this podcast');
        }

        return $this->response->noContent();
    }

    /**
     * Remove the specified podcast from storage.
     *
     * @param  \App\Podcast  $podcast
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Podcast $podcast, int $id)
    {
        try {
            $result = $podcast->newQueryWithoutScope(PodcastScope::class)
                ->findOrFail($id)
                ->delete();
        } catch (ModelNotFoundException $e) {
            return $this->response->errorNotFound('Application can\'t find podcast with such id');
        } catch (\Throwable $t) {
            return $this->response->errorInternal('Application was unable to save this podcast');
        }

        return $this->response->noContent();
    }


    /**
     * Approves podcast and publish it.
     *
     * @param Podcast $podcast
     * @param int $id
     */
    public function approve(Podcast $podcast, int $id) {
        try {
            $result = $podcast::withoutGlobalScope(PodcastScope::class)
                ->findOrFail($id);
            $result->status = Podcast::STATUS_PUBLISHED;
            $result->save();
        } catch (ModelNotFoundException $e) {
            return $this->response->errorNotFound('Application can\'t find podcast with such id');
        } catch (\Throwable $t) {
            return $this->response->errorInternal('Application was unable to approve this podcast');
        }

        return $this->response->noContent();
    }


}