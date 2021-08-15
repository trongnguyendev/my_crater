<?php

namespace Crater\Http\Controllers\V1\Comment;

use Crater\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crater\Http\Requests\CommentsRequest;
use Crater\Models\Comment;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit')? $request->limit : 10;
        
        $comments = Comment::leftJoin('users', 'users.id', '=', 'comments.user_id')
                ->applyFilter($request->only([
                    'search',
                    'orderBy',
                    'orderByField'
                ]))
                ->select('comments.*', 'users.name as name_user_comment')
                ->latest()
                ->paginateData($limit);
        return response()->json([
            'comments' => $comments,
            'totalCommentCount' => Comment::count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentsRequest $request)
    {
        $comment = Comment::createComment($request);

        return response()->json([
            'comment' => $comment,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return response()->json([
            'comment' => $comment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentsRequest $request, Comment $comment)
    {
        $comment = $comment->updateComment($request);

        return response()->json([
            'comment' => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        Comment::destroy($comment->id);

        return response()->json([
            'success' => true,
        ]);
    }
}
