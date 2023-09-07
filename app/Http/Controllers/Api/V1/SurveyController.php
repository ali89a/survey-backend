<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSurveyRequest;
use App\Http\Requests\Api\V1\SurveyAssignRequest;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\SurveyResource;
use App\Http\Resources\UserResource;
use App\Models\Answer;
use App\Models\Feedback;
use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use App\Notifications\SurveyAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function authUserSurvey()
    {
        try {
            $surveys = Survey::with(['createdBy','questions'])->where('assigned_to', authUser(true))->latest()->get();
            return response()->successResponse('Survey List', SurveyResource::collection($surveys));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }

    public function index()
    {
        $data = Survey::with(['createdBy'])->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Successful Retrieved Surveys',
            'data' => SurveyResource::collection($data)
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
    public function store(StoreSurveyRequest $request)
    {
        try {
            $data = Survey::create($request->validated());
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Survey created successfully',
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
            'message' => 'Survey creation Failed',
            'data' => null
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Survey::where('id', $id)->first();
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Survey Retried successfully',
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
            'message' => 'Survey Retried Failed',
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
            $data = Survey::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'updated_by' => authUser(true),
            ]);
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Survey Updated successfully',
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
            'message' => 'Survey update Failed',
            'data' => null
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = Survey::where('id', $id)->delete();
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Survey Deleted successfully',
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
            'message' => 'Survey delete Failed',
            'data' => null
        ], 500);
    }

    public function assignSurvey(Request $request)
    {
        $user = User::find(1);
        $task = Survey::find(1);
        Notification::send($user, new SurveyAssignedNotification($user, $task));
    }

    public function getUsersForAssignSurvey()
    {
        try {
            $users = User::latest()->get();
            return response()->successResponse('Users List', UserResource::collection($users->except(request()->user()->id)));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }

    public function assignUser(SurveyAssignRequest $request)
    {
        try {
            $task = Survey::find($request->id);
            $data = $request->only('assigned_to');
            $data['status'] = 'in-progress';
            $data['assigned_by'] = authUser(true);
            if ($task->status === 'open') {
                $task->update($data);

                //send email to the user
                $user = User::find($request->assigned_to);
                Notification::send($user, new SurveyAssignedNotification($user, $task));
            }


            return response()->successResponse('Used Assigned to task successfully', new SurveyResource($task));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }

    public function surveyDetails($id)
    {
        try {
            $survey = Survey::find($id);
            return response()->successResponse('Survey Details Retrieved', $survey);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }

    public function getSurveysForQuestion()
    {
        try {
            $survey = authUser()->surveys;
            return response()->successResponse('Surveys List', SurveyResource::collection($survey));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }
    public function getSurveysQuestions($id)
    {
        try {
            $questions = Question::where('survey_id',$id)->get();
            return response()->successResponse('Questions List', QuestionResource::collection($questions));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }
    public function getSurveysAnswers($survey_id)
    {
        try {
            $feedbacks = Feedback::with(['survey','answers'])->where('survey_id',$survey_id)->latest()->get();
            return response()->successResponse('Feedback List', FeedbackResource::collection($feedbacks));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse();
        }
    }


}
