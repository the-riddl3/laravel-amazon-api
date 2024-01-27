<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        Gate::authorize('admin');

        return response()->json([
            'category' => Category::query()->create([
                Category::NAME => $request->get('name'),
            ])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        Gate::authorize('admin');
        $category->update($request->only(['name']));

        return response()->json([
            'message' => 'Category updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        Gate::authorize('admin');
        $category->delete();

        return response()->json([
            'message' => 'Category deleted'
        ]);
    }
}
