<?php

namespace App\Controllres;

use App\Core\Controller;
use App\Core\Request;
use App\Core\SessionManager;
use App\Core\Validator;
use App\Models\Tasks;
use DateTime;
use Google\Service\Analytics\Resource\Data;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->setLayout("adminPanel");
    }
    public function index()
    {
        $tasks = new Tasks;
        $data = $tasks->getAllTaskByUser($_SESSION['user_id']);
        return $this->view('dashboard', ['data' => $data]);
    }
    public function createTask(Request $request)
    {
        $validtor = new Validator;
        $fields = [
            'title' => 'required',
            'priority' => 'required ',
            'due_date' => 'required',
            'status' => 'required',
            'category' => 'required',
            'description' => 'required',
        ];
        if ($request->isPost()) {
            if ($validtor->validation($request->body(), $fields)) {
                echo "Error";
            } else {
                $body = $request->body();
                $data = [
                    'title' => $body['title'],
                    'description' => $body['description'],
                    'due_date' => $body['due_date'],
                    'status' => $body['status'],
                    'user_id' => $_SESSION['user_id'],
                    'priority_level_id' => $body['priority'],
                    'category_id' => $body['category']
                ];
                $task = new Tasks;
                $task->insertToTasks($data);
                SessionManager::set('success', 'Insert To Database Success!');
                return $this->redirectTo('dashboard');
            }
            exit;
        }
        return $this->view('newTask');
    }
    public function edit($id)
    {
        $task = new Tasks;
        $data = $task->getTasksById($id);
        return $this->view('updateTask', $data[0]);
    }
    public function updateTask(Request $request)
    {
        var_dump($request->body());
        $body = $request->body();
        $id = $body['id'];
        $data = [
            'title' => $body['title'],
            'description' => $body['description'],
            'due_date' => $body['due_date'],
            'status' => $body['status'],
            'user_id' => $_SESSION['user_id'],
            'priority_level_id' => $body['priority'],
            'category_id' => $body['category']
        ];
        $task = new Tasks;
        $task->updateTaskById($id, $data);
        SessionManager::set('success', 'Updated Success!');
        return $this->redirectTo('dashboard');
    }
    public function delete($id)
    {
        $task = new Tasks;
        $task->deleteTaskById($id);
        SessionManager::set('success', 'Delete Success!');
        return $this->redirectTo('dashboard');
    }
    public function logout()
    {
        SessionManager::destroy();
        return $this->redirectTo('sigup');
    }
    public function filter(Request $request)
    {
        $task = new Tasks;
        $data = $task->filterByTask(intval($_SESSION['user_id']), $request->body());
        return $this->view('dashboard', ['data' => $data]);
        exit;
    }
}
