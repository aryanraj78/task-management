<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Traits\ApiResponder;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\DB;


class TaskController extends Controller
{
    use ApiResponder;

    public function createTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|max:200',
            'status' => 'required|max:200',

        ]);

        $data = [];

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['assigned_user_id'] = auth()->user()->id;
            $input['created_by'] = auth()->user()->id;
            $task = Task::create($input);
            $task_status = new TaskStatus;
            $task_status->task_id = $task->id;
            $task_status->status = $request->status;
            $task_status->save();
            DB::commit();

            return $this->success($data,'Task Created successfully!!', 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error('Something went wrong!!', 203 ,$e->getMessage());
        }
        
    }

    public function taskList(Request $request)
    {
        
        $data = [];
        $user_id = auth()->user()->id;
        $task_list = Task::with('statusList')->where('assigned_user_id',$user_id)->paginate(10);
        $data['task_list'] = $task_list;
        return $this->success($data,'Task List', 200);          
            
        
    }

    public function updateTask(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|max:200',

        ]);

        $data = [];
        $user_id = auth()->user()->id;

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }
        $task = Task::where('id',$request->task_id)->where('assigned_user_id',$user_id)->first();
        if(!$task)
        {
            return $this->error('You do not have access for this task', 422 ,$data);

        }
        try {
            DB::beginTransaction();
            $task->status = $request->status;
            $task->update();
            $task_status = new TaskStatus;
            $task_status->task_id = $task->id;
            $task_status->status = $request->status;
            $task_status->save();
            DB::commit();

            return $this->success($data,'Task is updated successfully!!', 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error('Something went wrong!!', 203 ,$e->getMessage());
        }         
            
        
    }

    public function deleteTask(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',

        ]);

        $data = [];
        $user_id = auth()->user()->id;

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }
        $task = Task::where('id',$request->task_id)->where('created_by',$user_id)->first();
        if(!$task)
        {
            return $this->error('You do not have access for this task', 422 ,$data);

        }
        
            $task->delete();          

            return $this->success($data,'Task is deleted successfully!!', 200);
                
            
        
    }

    public function getTask(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',

        ]);

        $data = [];
        $user_id = auth()->user()->id;

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }
        $task = Task::with('statusList')->where('id',$request->task_id)->where('created_by',$user_id)->first();
        if(!$task)
        {
            return $this->error('You do not have access for this task', 422 ,$data);

        }
        
            $data['task'] = $task;          

            return $this->success($data,'Task get successfully!!', 200);
                
            
        
    }

    //
}
