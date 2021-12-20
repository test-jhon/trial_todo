<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTodoRequest;

class TodoController extends Controller
{
    public function index (Request $request)
    {
        $payload = $request->query('done');

        return Todo::where('done', $payload)->get();
    }

    public function store (StoreTodoRequest $request)
    {
        $q = Todo::create($request->validated());

        // return response('', 201)->header('Location', url("/api/todos/{$q->id}"));
        return response($q, 201);
    }

    public function show ($todo_id)
    {
        return Todo::find($todo_id);
    }

    public function updateStatus (Request $request, $todo_id)
    {
        $q = Todo::find($todo_id);
        $q->done = $request->get('done');
        $q->save();

        return response()->json($q);
    }
}
