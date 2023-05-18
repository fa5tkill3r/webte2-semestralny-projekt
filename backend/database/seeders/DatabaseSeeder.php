<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Http\Controllers\TeacherController;
use App\Models\Assignment;
use App\Models\Set;
use App\Models\SetTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Jozko',
            'last_name' => 'Mrkvicka',
            'email' => 'xmrkvicka@stuba.sk',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        $teacher = User::create([
            'first_name' => 'Pan',
            'last_name' => 'Ucitel',
            'email' => 'pan.ucitel@stuba.sk',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        $blokovka01pr = file_get_contents('database/seeders/blokovka01pr.tex');
        $odozva02pr = file_get_contents('database/seeders/odozva02pr.tex');

        $task1 = TeacherController::parseTex($blokovka01pr, "blokovka01");
        $task2 = TeacherController::parseTex($odozva02pr, "odozva02");

        $task3 = Task::create([
            'name' => 'SimplePriklad',
        ]);

        $task4 = Task::create([
            'name' => 'SimplePriklad2',
        ]);

        $task3->variants()->create([
            'name' => 'Variant1',
            'task' => '1+1',
            'solution' => '2',
        ]);

        $task4->variants()->create([
            'name' => 'Variant1',
            'task' => '5*5',
            'solution' => '25',
        ]);



        $set = Set::create([
            'name' => 'Test',
            'start' => date('Y-m-d H:i:s'),
            'end' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'created_by' => $teacher->id,
        ]);

        SetTask::create([
            'set_id' => $set->id,
            'task_id' => $task1->id,
            'max_points' => 5,
        ]);

        SetTask::create([
            'set_id' => $set->id,
            'task_id' => $task2->id,
            'max_points' => 5,
        ]);



        Assignment::create([
            'user_id' => $user->id,
            'based_on_set_id' => $set->id,
        ]);

        $simpleSet = Set::create([
            'name' => 'SimpleSet',
            'created_by' => $teacher->id,
        ]);


        SetTask::create([
            'set_id' => $simpleSet->id,
            'task_id' => $task3->id,
            'max_points' => 10,
        ]);


        SetTask::create([
            'set_id' => $simpleSet->id,
            'task_id' => $task4->id,
            'max_points' => 10,
        ]);

        Assignment::create([
            'user_id' => $user->id,
            'based_on_set_id' => $simpleSet->id,
        ]);






    }
}
