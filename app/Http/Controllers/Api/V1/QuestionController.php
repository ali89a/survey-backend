<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreFeedbackRequest;
use App\Models\Feedback;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = Question::create($request->all());
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Question created successfully',
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
            'message' => 'Question creation Failed',
            'data' => null
        ], 500);
    }
}
