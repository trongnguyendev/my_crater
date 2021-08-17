<?php

namespace Crater\Http\Controllers\V1\Type;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests\TypeRequest;
use Illuminate\Http\Request;
use Crater\Models\Type;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        

        $limit = $request->has('limit') ? $request->limit : 10;

        $types = Type::applyFilters($request->only([
                'search',
                'orderBy',
                'orderByField'
            ]))
            ->select('types.*')
            ->latest()
            ->paginateData($limit);

        return response()->json([
            'types' => $types,
            'typeTotalCount' => Type::count()
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
    public function store(TypeRequest $request)
    {
        $type = Type::createType($request);

        return response()->json([
            'type', $type
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        return response()->json([
            'type' => $type
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
    public function update(TypeRequest $request, Type $type)
    {
        $type = $type->updateType($request);

        return response()->json([
            'type' => $type,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Type::destroy($id);

        return response()->json([
            'success' => true
        ]);
    }
}
