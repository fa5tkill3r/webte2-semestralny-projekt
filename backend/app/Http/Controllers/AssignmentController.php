<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateAssignmentRequest;
use App\Models\Assignment;
use App\Models\AssignmentTaskVariant;
use App\Models\Set;
use App\Models\TaskVariant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AssignmentController extends Controller
{
    public function generateAssignment(GenerateAssignmentRequest $request): JsonResponse
    {
        $request->validated();
        $set = Set::find($request->set_id);

        $assignment = Assignment::create([
            'user_id' => auth()->user()->id,
            'set_id' => $set->id,
            'based_on_set_id' => $set->id,
        ]);


        $setTasks = $set->setTasks()->get();


        foreach ($setTasks as $setTask) {

            $taskVariant = $setTask->task()->get()->first()->randomVariant();

            AssignmentTaskVariant::create([
                'assignment_id' => $assignment->id,
                'task_variant_id' => $taskVariant->id,
                'set_task_id' => $setTask->id,
            ]);

        }

        return response()->json([
            'message' => 'Successfully generated assignment',

        ], 200);
    }

    public function getAssignment(Request $request, $id): JsonResponse
    {
        $assignment = Assignment::find($id);
        $assignment->task_variants = $assignment->taskVariants()->get();

        // add max points to each task variant
        foreach ($assignment->task_variants as $task_variant) {
            $task_variant->max_points = AssignmentTaskVariant::where('assignment_id', $assignment->id)->where('task_variant_id', $task_variant->id)->first()->setTask()->first()->max_points;
        }


        return response()->json([
            'assignment' => $assignment,
        ], 200);
    }

    public function getAssignments(): JsonResponse
    {
        $assignments = User::find(auth()->user()->id)->assignments()->get();

        return response()->json([
            'assignments' => $assignments,
        ], 200);
    }

    public function getImage(Request $request, $taskVariant): \Illuminate\Http\Response
    {
        // get image from storage
        $storagePath = "public\\$taskVariant\\$request->image";
        $path = storage_path("app\\$storagePath");

        if (!file_exists($path)) {
            return Response::make('File not found.', 404);
        }

        $file = file_get_contents($path);
        $type = mime_content_type($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($file);

        return Response::make($file, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'inline; filename="' . $request->image . '"'
        ]);
    }

    public function getTaskVariant(Request $request, $taskVariantId) {
        $task = TaskVariant::find($taskVariantId)->get();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'task' => $task,
        ], 200);
    }
}
