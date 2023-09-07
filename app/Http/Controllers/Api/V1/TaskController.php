<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\TaskAssignRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function authUserTask(){
        try{
            $tasks =  Task::with(['createdBy','assignedBy','assignedTo'])->where('assigned_to',authUser(true))->latest()->get();
            return response()->successResponse('Tasks List', TaskResource::collection($tasks));
        }catch(Exception $e){
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }
    public function index()
    {
        $data = Task::with(['createdBy','assignedBy','assignedTo'])->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'successful Retrieved Tasks',
            'data' => TaskResource::collection($data)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $data = Task::create($request->validated());
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task created successfully',
                    'data' => $data
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], $e->getCode());
        }
        return response()->json([
            'success' => false,
            'message' => 'Task creation Failed',
            'data' => null
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Task::where('id', $id)->first();
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task Retried successfully',
                    'data' => $data
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], $e->getCode());
        }
        return response()->json([
            'success' => false,
            'message' => 'Task Retried Failed',
            'data' => null
        ], 500);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this->validate($request, [
            'title' => 'required',
            'description' => 'sometimes',
            'deadline' => 'required'
        ]);
        try {
            $data = Task::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'updated_by' => authUser(true),
            ]);
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task Updated successfully',
                    'data' => $data,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], $e->getCode());
        }
        return response()->json([
            'success' => false,
            'message' => 'Task update Failed',
            'data' => null
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = Task::where('id', $id)->delete();
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task Deleted successfully',
                    'data' => $data
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], $e->getCode());
        }
        return response()->json([
            'success' => false,
            'message' => 'Task delete Failed',
            'data' => null
        ], 500);
    }

    public function assignTask(Request $request)
    {
        $user=User::find(1);
        $task=Task::find(1);
        Notification::send($user, new TaskAssignedNotification($user, $task));
    }

    public function getUsersForAssignTask(){
        try{
            $users = User::latest()->get();
            return response()->successResponse('Users List', UserResource::collection($users->except(request()->user()->id)));
        }catch(Exception $e){
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }

    public function assignUser(TaskAssignRequest $request){
        try{
            $task =  Task::find($request->id);
            $data= $request->only('assigned_to');
            $data['status'] = 'in-progress';
            $data['assigned_by'] = authUser(true);
            if($task->status === 'open'){
                $task->update($data);

                //send email to the user
                $user = User::find($request->assigned_to);
                Notification::send($user, new TaskAssignedNotification($user, $task));
            }


            return response()->successResponse('Used Assigned to task successfully', new TaskResource($task));
        }catch(Exception $e){
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }

}
