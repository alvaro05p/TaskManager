<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'color' => 'required|max:7'
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->color = $request->color;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Nueva categoría agregada.');
    }

    /**
     * Display the specified resource.
     */
    public function show($category)
    {
        $category = Category::with('todos')->findOrFail($category);
        return view('categories.show', ['category' => $category]);
    }

    public function edit($category)
    {
        $category = Category::with('todos')->findOrFail($category);
        return view('categories.edit', ['category' => $category]);
    }   


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $category)
    {
        $category = Category::find($category);
        $category->name = $request->name;
        $category->color = $request->color;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category)
    {
        $category = Category::find($category);
        $category->todos()->each(function($todo){
            $todo->delete();
        });
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada');
    }
}
