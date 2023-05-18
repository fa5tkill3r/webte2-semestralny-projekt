<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function parse(Request $request) {
        $text = $request->getContent();
        $taskName = $request->taskname;

        $regex = '/\\\\section\\*{([^}]*)}([\\s\\S]*?)(?=\\\\section\\*{[^}]*}|\\\\end{document})/';
        preg_match_all($regex, $text, $matches);


        if (!empty($matches[0])) {
            $task = Task::create([
                'name' => $taskName,
            ]);

            try {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $sectionTitle = $matches[1][$i];
                    $sectionContent = $matches[2][$i];

                    echo "Section title: " . $sectionTitle . "\n";

                    $variant = $task->variants()->create([
                        'name' => $sectionTitle,
                    ]);

                    $sectionContent = preg_replace_callback('/\\\\includegraphics{([^}]*)}/', function ($matches) use (&$extracted_filenames, $variant) {
                        $filename_with_path = $matches[1];
                        $api = env('APP_URL') . "/api/task/$variant->id/image?filename=";
                        echo "Filename with path: " . $filename_with_path . "\n";
                        $filename = basename($filename_with_path);
                        $extracted_filenames[] = $filename_with_path;
                        return '$$\\includegraphics[totalheight=100]{' . $api . $filename . '}$$';
                    }, $sectionContent);



                    if (preg_match('/\\\\begin\{task}([\\s\\S]*?)\\\\end\{task}/', $sectionContent, $task_match)) {
                        $task_content = $task_match[1];

                        echo "Task content: " . $task_content . "\n";
                    } else {
                        throw new \Exception("Task content not found.");
                    }

                    if (preg_match('/\\\\begin\{solution\}\s*\\\\begin\{equation\*\}([\s\S]*?)\\\\end\{equation\*\}\s*\\\\end\{solution\}/', $sectionContent, $solution_match)) {
                        $solution_content = $solution_match[1];
                        $solution_content = ltrim($solution_content);
                        $solution_content = rtrim($solution_content);

                        echo "Solution content: " . $solution_content . "\n";
                    } else {
                        throw new \Exception("Solution content not found.");
                    }

                    $variant->update([
                        'task' => $task_content,
                        'solution' => $solution_content,
                    ]);
                }
            } catch (\Exception $e) {
                $task->variants()->delete();
                $task->delete();
                throw $e;
            }
        } else {
            echo "No matches found.";
        }
    }
}
