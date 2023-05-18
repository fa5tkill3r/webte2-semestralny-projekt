<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateAssignmentRequest;
use App\Models\Assignment;
use App\Models\AssignmentTaskVariant;
use App\Models\Set;
use App\Models\SetTask;
use App\Models\TaskVariant;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use GuzzleHttp\Client;

class AssignmentController extends Controller
{

    public function getAllTasks(): JsonResponse
    {
        $tasks = Task::all();

        return response()->json([
            'tasks' => $tasks,
        ], 200);
    }

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


    public function generateTaskVariant(Request $request, $assigmentId, $taskId)
    {
        $assignment = Assignment::find($assigmentId);
        $setTask = SetTask::where('set_id', $assignment->based_on_set_id)->where('task_id', $taskId)->first();
        $taskVariant = $setTask->task()->first()->randomVariant();

        AssignmentTaskVariant::create([
            'assignment_id' => $assignment->id,
            'task_variant_id' => $taskVariant->id,
            'set_task_id' => $setTask->id,
        ]);

        return $this->getAssignment($request, $assigmentId);
    }

    public function getAssignment(Request $request, $id): JsonResponse
    {
        $assignmentTaskVariants = AssignmentTaskVariant::where('assignment_id', $id)->get();
        $assignment = Assignment::find($id);

        if ($assignment->user_id != auth()->user()->id && auth()->user()->role != 'teacher') {
            return response()->json([
                'message' => 'You are not allowed to view this assignment',
            ], 403);
        }

        $assignment->set = $assignment->set()->first();
        $assignment->set->set_tasks = $assignment->set->setTasks()->get();
        $assignment->set->set_tasks->each(function ($setTask) use ($assignmentTaskVariants) {
            $setTask->task = $setTask->task()->first();
            $assignmentTaskVariant = $assignmentTaskVariants->where('set_task_id', $setTask->id)->first();
            if ($assignmentTaskVariant != null) {
                $setTask->task->taskVariant = $assignmentTaskVariant->taskVariant()->first();
                $setTask->task->taskVariant->user_solution = $assignmentTaskVariant->solution;
                $setTask->task->taskVariant->correct = $assignmentTaskVariant->correct;
            }
        });


        return response()->json([
            'assignment' => $assignment,
        ], 200);
    }

    public function getAssignments(): JsonResponse
    {
        $assignments = User::find(auth()->user()->id)->assignments()->get();
        $assignments->each(function ($assignment) {
            $assignment->set = $assignment->set()->first();
            $setTasks = $assignment->set->setTasks()->get();
            $assignment->max_points = 0;
            $assignment->tasks_count = $setTasks->count();
            $assignment->tasks_done = $assignment->assignmentTaskVariants()->count();
            $maxPoints = null;
            foreach ($setTasks as $setTask) {
                $assignment->max_points += $setTask->max_points;
                $maxPoints = $setTask->max_points;
            }
            $assignment->points = null;
            $assignmentTaskVariants = $assignment->assignmentTaskVariants()->get();
            foreach ($assignmentTaskVariants as $assignmentTaskVariant) {
                if ($assignment->points == null)
                    $assignment->points = 0;

                if ($assignmentTaskVariant->correct) {
                    $assignment->points += $maxPoints;
                }
            }

            $assignment->state = "new";
            if ($assignment->set->setTasks()->count() == $assignment->assignmentTaskVariants()->where('correct', true)->count())
                $assignment->state = "done";
            else if ($assignment->assignmentTaskVariants()->count() > 0)
                $assignment->state = "in_progress";

            $assignment->teacher = $assignment->set->createdBy()->first();
        });

        $assignments = $assignments->filter(function ($assignment) {
            if ($assignment->set->start == null)
                return true;
            return $assignment->set->start <= now();
        });

        $assignments = $assignments->sortByDesc(function ($assignment) {
            return $assignment->state !== "done";
        })->sortByDesc(function ($assignment) {
            return $assignment->set->end;
        });

        $assignments = $assignments->values()->all();

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

    public function getTaskVariantByNumber(Request $request, $assignmentId, $taskNumber): JsonResponse
    {
        $assigmentTaskVariant = AssignmentTaskVariant::where('assignment_id', $assignmentId)->where('task_number', $taskNumber)->first();
        $task = TaskVariant::find($assigmentTaskVariant->task_variant_id);
        $task->task_number = (int)$taskNumber;
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

    public function addSolution(Request $request, $assignmentId, $taskVariantId): JsonResponse
    {
        $assignmentTaskVariant = AssignmentTaskVariant::where('assignment_id', $assignmentId)->where('task_variant_id', $taskVariantId)->first();

        if ($assignmentTaskVariant->assignment()->first()->finished_at != null) {
            return response()->json([
                'message' => 'Assignment is finished',
            ], 400);
        }
        if ($assignmentTaskVariant->solution != null) {
            return response()->json([
                'message' => 'Solution already exists',
            ], 400);
        }

        $assignmentTaskVariant->solution = $request->solution;
        $assignmentTaskVariant->save();

        $this->checkSolution( $assignmentTaskVariant, $taskVariantId);

        $setTasks = SetTask::where('set_id', $assignmentTaskVariant->assignment()->first()->based_on_set_id)->get();
        $assignmentTaskVariants = AssignmentTaskVariant::where('assignment_id', $assignmentTaskVariant->assignment_id)->get();

        if ($setTasks->count() == $assignmentTaskVariants->count()) {
            $assignment = Assignment::find($assignmentTaskVariant->assignment_id);
            $assignment->finished_at = now();
            $assignment->save();
        }

        return $this->getAssignment($request, $assignmentTaskVariant->assignment_id);
    }


    public function checkSolution($assignmentTaskVariant, $taskVariantId) {
        $assignmentTaskVariantSolution = $assignmentTaskVariant->solution;

        $task = TaskVariant::find($taskVariantId);
        $taskSolution = $task->solution;

        $client = new Client([
            'base_uri' => env('VALIDATOR_URL')
        ]);

        $data = [
            'expr1' => $assignmentTaskVariantSolution,
            'expr2' => $taskSolution,
        ];

        $response = $client->post('/compare', [
            'json' => $data,
            'verify' => false
        ]);

        $responseData = json_decode($response->getBody(), true);

        if($responseData["result"]== 1){
            $assignmentTaskVariant->correct = true;
            $assignmentTaskVariant->save();
        }else if($responseData["result"] == 0){
            $assignmentTaskVariant->correct = false;
            $assignmentTaskVariant->save();
        }
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
