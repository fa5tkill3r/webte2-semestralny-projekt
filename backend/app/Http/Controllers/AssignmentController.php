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


        $taskNumber = 1;
        foreach ($setTasks as $setTask) {

            $taskVariant = $setTask->task()->get()->first()->randomVariant();

            AssignmentTaskVariant::create([
                'assignment_id' => $assignment->id,
                'task_variant_id' => $taskVariant->id,
                'set_task_id' => $setTask->id,
                'task_number' => $taskNumber++,
            ]);

        }

        return response()->json([
            'message' => 'Successfully generated assignment',

        ], 200);
    }

    public function getAssignment(Request $request, $id): JsonResponse
    {
        $assignmentTaskVariants = AssignmentTaskVariant::where('assignment_id', $id)->get();
        $assignment = Assignment::find($id);

        $taskVariants = [];
        foreach ($assignmentTaskVariants as $assignmentTaskVariant) {
            $taskVariant = $assignmentTaskVariant->taskVariant()->first();
            if ($assignment->finished_at != null) {
                $taskVariant->makeVisible('solution');
            }
            $taskVariant->user_solution = $assignmentTaskVariant->solution;
            $taskVariant->correct = $assignmentTaskVariant->correct;
            $taskVariant->task_number = $assignmentTaskVariant->task_number;
            $taskVariant->max_points = $assignmentTaskVariant->setTask()->first()->max_points;
            $taskVariants[] = $taskVariant;
        }

        $assignment->task_variants = $taskVariants;


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
        $storagePath = "public\\$taskVariant\\$request->filename";
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

    public function getTaskVariantByNumber(Request $request, $assignmentId, $taskNumber): JsonResponse {
        $assigmentTaskVariant = AssignmentTaskVariant::where('assignment_id', $assignmentId)->where('task_number', $taskNumber)->first();
        $task = TaskVariant::find($assigmentTaskVariant->task_variant_id);
        $task->task_number = (int) $taskNumber;
        $task->max_points = $assigmentTaskVariant->setTask()->first()->max_points;

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'task_variant' => $task,
        ], 200);
    }

    public function getTaskVariant(Request $request, $taskVariantId): JsonResponse
    {
        $task = TaskVariant::find($taskVariantId);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'task_variant' => $task,
        ], 200);
    }

    public function addSolution(Request $request, $assignmentId, $taskNumber): JsonResponse {
        $assignmentTaskVariant = AssignmentTaskVariant::where('assignment_id', $assignmentId)->where('task_number', $taskNumber)->first();

        if ($assignmentTaskVariant->assignment()->first()->finished_at != null) {
            return response()->json([
                'message' => 'Assignment is finished',
            ], 400);
        }

        $assignmentTaskVariant->solution = $request->solution;
        $assignmentTaskVariant->save();

        return response()->json([
            'message' => 'Successfully added solution',
        ], 200);
    }

    public function submitAssignment(Request $request, $assignmentId): JsonResponse {
        $assignment = Assignment::find($assignmentId);
        $assignment->finished_at = now();
        $assignment->save();

        return response()->json([
            'message' => 'Successfully submitted assignment',
        ], 200);
    }
}
