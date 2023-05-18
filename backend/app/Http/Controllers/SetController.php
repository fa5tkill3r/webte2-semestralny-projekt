<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\SetTask;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SetController extends Controller
{
    
    public function createSet(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'tasks' => 'required|array',
            'max_points' => 'required|integer|min:0',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $userId = Auth::id();
    
        $set = Set::create([
            'name' => $request->input('name'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'created_by' => $userId,
        ]);
    
        foreach ($request->input('tasks') as $taskId) {
            $task = Task::find($taskId);
    
            if ($task) {
                SetTask::create([
                    'set_id' => $set->id,
                    'task_id' => $taskId,
                    'max_points' => $request->input('max_points'),
                ]);
            } else {
                // Log the taskId if the task is not found
                \Log::warning("Task not found for ID: $taskId");
            }
        }
    
        return response()->json([
            'message' => 'Set created successfully',
            'set' => $set,
        ], 200);
    }
    
}