<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    /**
     * Get components for a subject (for MAPEH subjects)
     */
    public function getComponents(Request $request, $id): JsonResponse
    {
        try {
            $subject = Subject::with('components')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'components' => $subject->components
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found or error retrieving components'
            ], 404);
        }
    }
}