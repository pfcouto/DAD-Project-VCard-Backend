<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateDefaultCategoryRequest;
use App\Http\Resources\DefaultCategoryResource;
use App\Models\DefaultCategory;
use Illuminate\Http\Request;

class DefaultCategoryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? '';

        $qry = DefaultCategory::query();

        if ($type) {
            $qry->where('type', $type);
        }

        return DefaultCategoryResource::collection($qry->orderBy('id', 'ASC')->paginate(10));
    }

    public function show(DefaultCategory $defaultCategory)
    {
        return new DefaultCategoryResource($defaultCategory);
    }

    public function store(StoreUpdateDefaultCategoryRequest $request)
    {
        $newDefaultCategory = DefaultCategory::create($request->validated());
        return new DefaultCategoryResource($newDefaultCategory);
    }

    public function update(StoreUpdateDefaultCategoryRequest $request, DefaultCategory $defaultCategory)
    {
        $defaultCategory->update($request->validated());
        return new DefaultCategoryResource($defaultCategory);
    }

    public function destroy(DefaultCategory $defaultCategory)
    {
        $defaultCategory->delete();
        return new DefaultCategoryResource($defaultCategory);
    }
}
