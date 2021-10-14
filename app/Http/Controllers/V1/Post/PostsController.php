<?php

namespace Crater\Http\Controllers\V1\Post;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests\PostsRequest;
use Crater\Http\Requests\PostUpdateRequest;
use Crater\Http\Requests\PostUpdateThumbnailRequest;
use Crater\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;

        $posts = Post::with(['comments', 'types'])
            ->applyFilters($request->only([
                'search',
                'content',
                'orderByField',
                'orderBy',
            ]))
            ->select('posts.*')
            ->latest()
            ->paginateData($limit);
            
        return response()->json([
            'posts' => $posts,
            'postTotalCount' => Post::count(),
        ]);
    }

    
    public function create()
    {
        //
        
    }

    
    public function store(PostsRequest $request)
    {
        $post = Post::createPost($request);

        if($request->has('types') && isset($post))
        {
            $post->types()->attach($request->types);
        }

        return response()->json([
            'post' => $post
        ]);
    }

    public function show(Post $post)
    {
        $post = $post->load(['comments', 'types']);

        return response()->json([
            'post' => $post,
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $post = $post->updatePost($request);

        if($request->has('types') && isset($post))
        {
            $post->types()->sync($request->types);
        }

        return response()->json([
            'post' => $post
        ]);
    }

    public function updateThumbnail(Request $request, Post $post)
    {

        $post = $post->updatePostthumbnail($request->thumbnail, $post);
        
        return response()->json([
            'post' => $post
        ]);
    }


    public function destroy(Request $request)
    {
    
        return response()->json([
            'success' => Post::destroy($request->ids)
        ]);
    }
}
