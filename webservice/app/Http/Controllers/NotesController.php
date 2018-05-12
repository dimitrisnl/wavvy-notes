<?php

namespace App\Http\Controllers;

use Auth;
use Log;
use DB;
use App\Note;
use App\Category;
use App\Http\Requests\NoteRequest;
use App\Transformers\NoteTransformer;

class NotesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort  = $this->getSort();
        $order = $this->getOrder();
        $limit = $this->getLimit();

        $category = $this->getCategory();

        $notes = Note::where('user_id', Auth::id())
                    ->where(function ($query) use($category) {
                        if ($category != -1)
                            $query->where('category_id', $category);
                    })
                    ->orderBy($sort, $order)
                    ->paginate($limit);      

        return $this->response(
            $this->transform->collection($notes, new NoteTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {

        $category = Category::where('id', $request->category)
                    ->where('user_id', Auth::id())
                    ->first();

        if (! $category) {
            return $this->responseWithNotFound('Category not found');
        }            

        Note::create([
            'name'        => $request->name,
            'body'        => $request->body,
            'category_id' => $request->category,
            'user_id'     => Auth::id(),
        ]);

        return $this->response([
            'result' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Note::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

        if (! $note) {
            return $this->responseWithNotFound('Note not found');
        }

        return $this->response(
            $this->transform->item($note, new NoteTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $request, $id)
    {
        $note = Note::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

        if (! $note) {
            return $this->responseWithNotFound('Note not found');
        }

        $note->name = $request->name;
        $note->body = $request->body;

        $note->save();

        return $this->response([
            'result' => 'success',
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
        $note = Note::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();
                    
        if (! $note) {
            return $this->responseWithNotFound('Note not found');
        }

        $note->delete();

        return $this->response([
            'result' => 'success',
        ]);
    }
}
