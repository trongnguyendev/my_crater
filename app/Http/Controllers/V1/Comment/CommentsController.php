<?php

namespace Crater\Http\Controllers\V1\Comment;

use Crater\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crater\Http\Requests\CommentsRequest;
use Crater\Models\Comment;

class CommentsController extends Controller
{
    
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

    public function create()
    {
        //
    }

    
    public function store(CommentsRequest $request)
    {
        $comment = Comment::createComment($request);

        return response()->json([
            'comment' => $comment,
        ]);
    }

    
    public function show(Comment $comment)
    {
        return response()->json([
            'comment' => $comment
        ]);
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(CommentsRequest $request, Comment $comment)
    {
        $comment = $comment->updateComment($request);

        return response()->json([
            'comment' => $comment
        ]);
    }

   
    public function destroy(Request $request)
    {
        
        return response()->json([
            'success' => Comment::destroy($request->ids)
        ]);
    }
}
