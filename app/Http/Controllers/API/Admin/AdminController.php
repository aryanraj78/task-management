<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Traits\ApiResponder;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    use ApiResponder;

    public function userList(Request $request)
    {
        
        $data = [];
        $user_list = User::with('assignedTask','assignedTask.statusList')->paginate(10);
        $data['user_list'] = $user_list;
        return $this->success($data,'User List', 200);
    }

    public function getUser(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',

        ]);

        $data = [];
        

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }
        $user = User::with('assignedTask','assignedTask.statusList')->where('id',$request->user_id)->first();
        $data['user'] = $user;
        return $this->success($data,'get User ', 200);
                
            
        
    }

    public function createTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assigned_user_id' => 'required|exists:users,id',
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
        $task_list = Task::with('user','statusList')->paginate(10);
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
        $task = Task::where('id',$request->task_id)->first();
        
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
        $task = Task::where('id',$request->task_id)->first();
        
        
            $task->delete();          

            return $this->success($data,'Task is deleted successfully!!', 200);
                
            
        
    }

    public function getTask(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',

        ]);

        $data = [];
        

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }
        $task = Task::with('user','statusList')->where('id',$request->task_id)->first();
        
        
            $data['task'] = $task;          

            return $this->success($data,'Task get successfully!!', 200);
                
            
        
    }

    public function assignTask(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'assigned_user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',

        ]);

        $data = [];
        $user_id = auth()->user()->id;

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return $this->error('All fields are required and should be valid', 422 ,$data);
           
        }
        $task = Task::where('id',$request->task_id)->first();
        $task->assigned_user_id = $request->assigned_user_id;
        
        $task->update();
            

        return $this->success($data,'Task is assigned successfully!!', 200);
                
            
        
    }

    //
}

