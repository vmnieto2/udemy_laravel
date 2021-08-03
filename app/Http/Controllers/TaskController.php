<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *  Display a list of all of the user's task
     *
     *  @param Request $request
     *  @return Response
     */

    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->orderBy('created_at', 'desc')->get();
        return view('tasks.index', ['tasks' => $tasks]);
    }

    /**
     *  Create a new task
     *
     *  @param Request $request
     *  @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
        ]);

        $request->user()->tasks()->create([
            'title' => $request->title
        ]);

        return redirect('/tasks');

        /* $task = new Task();
        $task->user_id = Auth::user()->id;
        $task->title = $request->title;
        $task->save(); */
    }

    /**
     * Destroy edit view
     *
     * @param  Task id  $id
     * @return Response
     */
    public function editView($id)
    {
        $task = Task::find($id);

        if (empty($task)) {
            return redirect('/tasks');
        }

        $this->authorize('verify', $task);

        return view('tasks.edit', ['task' => $task]);
    }

    /**
     * Edit the given task.
     *
     * @param  Request  $request
     * @param  Task id  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255'
        ]);

        $task = Task::find($id);
        if (empty($task)) {
            return redirect('/tasks');
        }

        $this->authorize('verify', $task);

        $task->title = $request->title;
        $task->save();
        return redirect('/tasks');
    }

    /**
     * Destroy the given task.
     *
     * @param  Task id  $id
     * @return Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            return redirect('/tasks');
        }

        $this->authorize('verify', $task);

        $task->delete();
        return redirect('/tasks');
    }
}
