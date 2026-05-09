<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\QuizCategory;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Get all quiz categories (public - no auth required)
     */
    public function getCategories()
    {
        $categories = QuizCategory::withCount('questions')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'code' => $category->code,
                    'name' => $category->name,
                    'description' => $category->description,
                    'pass_score' => $category->pass_score,
                    'total_questions' => $category->questions_count,
                ];
            }),
        ]);
    }

    /**
     * Start a new quiz attempt (public - no auth required)
     */
    public function startQuiz(Request $request, $categoryId)
    {
        $request->validate([
            'question_count' => 'integer|min:5|max:20',
        ]);
        
        $category = QuizCategory::findOrFail($categoryId);
        $questionCount = $request->input('question_count', 10);
        
        // Get random questions from this category
        $questions = QuizQuestion::where('category_id', $categoryId)
            ->inRandomOrder()
            ->limit($questionCount)
            ->get();
        
        if ($questions->count() < $questionCount) {
            return response()->json([
                'success' => false,
                'message' => 'Pas assez de questions disponibles dans cette catégorie.',
            ], 400);
        }
        
        // Create attempt without user_id for public access
        $attempt = QuizAttempt::create([
            'user_id' => null, // Public attempt
            'category_id' => $categoryId,
            'total_questions' => $questionCount,
            'started_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'data' => [
                'attempt_id' => $attempt->id,
                'category' => [
                    'id' => $category->id,
                    'code' => $category->code,
                    'name' => $category->name,
                ],
                'questions' => $questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'question_number' => $q->question_number,
                        'question_text' => $q->question_text,
                        'options' => $q->options,
                    ];
                }),
                'total_questions' => $questionCount,
                'pass_score' => $category->pass_score,
            ],
        ]);
    }

    /**
     * Submit quiz answers (public - no auth required)
     */
    public function submitQuiz(Request $request, $attemptId)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.answer' => 'required|integer|min:0|max:3',
        ]);
        
        $attempt = QuizAttempt::findOrFail($attemptId);
        
        if ($attempt->completed_at) {
            return response()->json([
                'success' => false,
                'message' => 'Ce quiz a déjà été soumis.',
            ], 400);
        }
        
        $score = 0;
        $results = [];
        
        foreach ($request->answers as $answer) {
            $question = QuizQuestion::find($answer['question_id']);
            $selectedOptionIndex = (int) $answer['answer'];
            
            $isCorrect = $selectedOptionIndex === $question->correct_option_index;
            
            if ($isCorrect) {
                $score++;
            }
            
            QuizAnswer::create([
                'attempt_id' => $attemptId,
                'question_id' => $question->id,
                'user_answer' => (string) $selectedOptionIndex,
                'is_correct' => $isCorrect,
            ]);
            
            $results[] = [
                'question_id' => $question->id,
                'question_number' => $question->question_number,
                'question_text' => $question->question_text,
                'options' => $question->options,
                'user_answer_index' => $selectedOptionIndex,
                'correct_answer_index' => $question->correct_option_index,
                'user_answer' => $question->options[$selectedOptionIndex] ?? '',
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
            ];
        }
        
        $passed = $score >= $attempt->category->pass_score;
        
        $attempt->update([
            'score' => $score,
            'passed' => $passed,
            'completed_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'data' => [
                'score' => $score,
                'total_questions' => $attempt->total_questions,
                'passed' => $passed,
                'pass_score' => $attempt->category->pass_score,
                'results' => $results,
            ],
        ]);
    }
}
