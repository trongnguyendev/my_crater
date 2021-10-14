<?php

namespace Crater\Http\Controllers\V1\Type;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests\TypeRequest;
use Illuminate\Http\Request;
use Crater\Models\Type;

class TypeController extends Controller
{
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

    public function create()
    {
        //
    }

    public function store(TypeRequest $request)
    {
        $type = Type::createType($request);

        return response()->json([
            'type' => $type
        ]);
    }

    public function show(Type $type)
    {
        return response()->json([
            'type' => $type
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(TypeRequest $request, Type $type)
    {
        $type = $type->updateType($request);

        return response()->json([
            'type' => $type,
        ]);
    }

    public function destroy(Request $request)
    {

        return response()->json([
            'success' => Type::destroy($request->ids)
        ]);
    }
}
