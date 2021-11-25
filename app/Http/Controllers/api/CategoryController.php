<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function store(StoreUpdateCategoryRequest $request)
    {
        $newCategory = Category::create($request->validated());
        return new CategoryResource($newCategory);
    }

    public function update(StoreUpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->assignedUsers()->detach();
        $category->delete();
        return new CategoryResource($category);
    }

    // public function getTransactionsOfCategory(Request $request, Category $category)
    // {
    //     //TaskResource::$format = 'detailed';
    //     if (!$request->has('include_assigned')) {
    //         return TaskResource::collection($user->tasks->sortByDesc('id'));
    //     } else {
    //         return TaskResource::collection($user->tasks->merge($user->assigedTasks)->sortByDesc('id'));
    //     }
    // }
}
