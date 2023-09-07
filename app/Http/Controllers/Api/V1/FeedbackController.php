<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreFeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FeedbackController extends Controller
{
    public function feedbackStore(StoreFeedbackRequest $request)
    {
        $validated = $request->validated();
        try {
            $data = Feedback::create(Arr::except($validated, 'answers'));
            //return 90000;
            foreach ($validated['answers'] as $answer) {

                $data->answers()->create($answer);
            }
        } catch (\Exception $e) {
            return $e;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], $e->getCode());
        }
        return response()->json([
            'success' => true,
            'message' => 'Feedback created successfully',
            'data' => $data
        ], 200);
    }
}
