<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\StoreComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class CommentController
 * @package App\Http\Controllers
 * @Resource("Comments", uri="/api/comments")
 */
class CommentController extends BaseController
{

    /**
     * Store a newly created comment in storage.
     *
     * @param  StoreComment $request
     * @param Comment $comment
     * @param int $podcastId
     * @return \Illuminate\Http\Response
     * @post("/{podcastId}")
     * @Versions({"v1"})
     * @Request({"author_name":"foo","author_email":"bar@foo.gmail","comment":"blablabla"}, headers={"Accept": "application/vnd.paydbytest.v1+json"}))
     * @Response(201, body={})
     */
    public function store(StoreComment $request, Comment $comment, int $podcastId)
    {
        try {
            $validated = $request->validated();
            $comment->fill($validated);
            $comment->podcast_id = $podcastId;
            $comment->comment_substr = substr($validated['comment'], 0, 191);
            $comment->save();
        } catch (\Throwable $t) {
            return $this->response->errorInternal('Application was unable to save this comment');
        }

        return $this->response->created();
    }


    /**
     * Remove the specified comment from storage.
     *
     * @param  Comment  $comment
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Request({"id":"1"}, headers={"Accept": "application/vnd.paydbytest.v1+json"}))
     * @Response(204)
     */
    public function destroy(Comment $comment, int $id)
    {
        try {
            $result = $comment->findOrFail($id);
            $result->comment_substr = $result->id;
            $result->save();
            $result->delete();
        } catch (ModelNotFoundException $e) {
            return $this->response->errorNotFound('Application can\'t find comment with such id');
        } catch (\Throwable $t) {
            return $this->response->errorInternal('Application was unable to delete this comment');
        }

        return $this->response->noContent();
    }


}
