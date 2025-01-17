<?php

namespace App\Models;

use App\Core\Model;
use Google\Service\Bigquery\Resource\Models;

class Tasks extends Model
{
    public function getAllTasks()
    {
        return $this->selectAll('tasks');
    }
    public function getTasksById($id)
    {
        return $this->selecById('tasks', $id);
    }
    public function getAllTaskByUser($id){
       return $this->selecBy('tasks','user_id',$id);
      
    }
    public function filterByTask($id,$conditions){
        return $this->filterBy('tasks',$id,$conditions);
    }
    public function insertToTasks($data)
    {
        $this->create('tasks', $data);
    }
    public function updateTaskById($id, $data)
    {
        $this->updateById('tasks', $id, $data);
    }
    public function deleteTaskById($id)
    {
        $this->deleteById('tasks', $id);
    }
}
