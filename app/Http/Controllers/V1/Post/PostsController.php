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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function store(PostsRequest $request)
    {
        $post = Post::createPost($request);

        if($request->has('types') && isset($post))
        {
            $types = explode(",", $request->types);

            $post->types()->attach($types);
        }

        return response()->json([
            'post' => $post
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post = $post->load(['comments', 'types']);

        return response()->json([
            'post' => $post,
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
     * @param  Post  $post   
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $post = $post->updatePost($request);

        if($request->has('types') && isset($post))
        {
            $types = explode(",", $request->types);

            $post->types()->sync($types);
        }

        return response()->json([
            'post' => $post,
        ]);
    }

    public function updateThumbnail(PostUpdateThumbnailRequest $request, Post $post)
    {

        $post = $post->updatePostthumbnail($request, $post);

        return response()->json([
            'post' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        

        return response()->json([
            'success' => Post::destroy($request->ids)
        ]);
    }
}
