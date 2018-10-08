<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\StoreComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentController extends BaseController
{

    /**
     * Store a newly created comment in storage.
     *
     * @param  StoreComment $request
     * @param Comment $comment
     * @param int $podcastId
     * @return \Illuminate\Http\Response
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
