<?php

namespace App\Http\Controllers;

use Auth;
use Log;
use App\Category;
use App\User;
use App\Note;
use App\Http\Requests\CategoryRequest;
use App\Transformers\CategoryTransformer;

class CategoriesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort = $this->getSort();
        $order = $this->getOrder();
        $limit = $this->getLimit();

        $categories = Category::where('user_id', Auth::id())
                            ->orderBy($sort, $order)
                            ->paginate($limit);

        return $this->response(
            $this->transform->collection($categories, new CategoryTransformer)
        );
    }

    public function fullList()
    {   
        $user = User::find(Auth::id());
        $categories = $user->categories;

        return $this->response(
            $this->transform->collection($categories, new CategoryTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {

        Category::create([
            'name' => $request->name, 
            'user_id' => Auth::id()
        ]);

        return $this->response(['result' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->first();

        if (! $category) {
            return $this->responseWithNotFound('Category not found');
        }

        return $this->response(
            $this->transform->item($category, new CategoryTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->first();

        if (! $category) {
            return $this->responseWithNotFound('Category not found');
        }

        $category->name = $request->get('name');
        $category->save();

        return $this->response(['result' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->first();

        if (! $category) {
            return $this->responseWithNotFound('Category not found');
        }

        // Note::where('category_id', $id)->delete();
        $category->delete();

        return $this->response(['result' => 'success']);
    }
}
