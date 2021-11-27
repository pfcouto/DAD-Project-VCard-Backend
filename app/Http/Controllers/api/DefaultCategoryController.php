<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateDefaultCategoryRequest;
use App\Http\Resources\DefaultCategoryResource;
use App\Models\DefaultCategory;
use Illuminate\Http\Request;

class DefaultCategoriesController extends Controller
{
    public function index()
    {
        return DefaultCategoryResource::collection(DefaultCategory::all());
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
        $defaultCategory->assignedUsers()->detach();
        $defaultCategory->delete();
        return new DefaultCategoryResource($defaultCategory);
    }
}
