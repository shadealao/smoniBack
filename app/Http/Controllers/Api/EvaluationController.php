<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationRequest;
use App\Http\Requests\UpdateEvaluationRequest;
use App\Models\Evaluation;
use App\Models\User;
use App\Http\Resources\EvaluationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

// use PDF;

class EvaluationController extends Controller
{
    public function index()
    {
        return EvaluationResource::collection(Evaluation::with(['student', 'instructor'])->paginate(10));
    }

    public function store(StoreEvaluationRequest $request)
    {
        $evaluation = Evaluation::create($request->validated());
        return new EvaluationResource($evaluation);
    }

    public function show(Evaluation $evaluation)
    {
        return new EvaluationResource($evaluation->load(['student', 'instructor']));
    }

    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        $evaluation->update($request->validated());
        return new EvaluationResource($evaluation);
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();
        return response()->noContent();
    }

    public function getByStudent(User $student)
    {
        return EvaluationResource::collection($student->evaluations()->with('instructor')->get());
    }

    public function completeEvaluation(Evaluation $evaluation)
    {
        // Logique pour calculer les résultats partiels et finaux
        $partialResults = $this->calculatePartialResults($evaluation);
        $finalResult = $partialResults > 50 ? 'positive' : 'negative';
        
        $evaluation->update([
            'partial_results' => $partialResults,
            'final_result' => $finalResult,
            'theory_hours' => $finalResult === 'positive' ? 20 : 30,
            'practice_hours' => $finalResult === 'positive' ? ($evaluation->gearbox_type === 'manual' ? 20 : 13) : 40
        ]);
        
        return new EvaluationResource($evaluation);
    }

    public function latest(User $student)
    {
        $evaluation = $student->evaluations()->latest()->first();
        return $evaluation ? new EvaluationResource($evaluation) : response(null, 204);
    }
    
    public function acceptProposal(User $student, Evaluation $evaluation)
    {
        // $this->authorize('update', $evaluation);
        
        $evaluation->update(['proposal_accepted' => true]);
        
        // Ici vous pourriez déclencher d'autres actions (création d'un contrat, etc.)
        
        return new EvaluationResource($evaluation->fresh());
    }
    
    public function stats()
    {
        $user = User::find(Auth::id());
        
        return response()->json([
            'total_evaluations' => Evaluation::count(),
            'positive_evaluations' => Evaluation::where('final_result', 'positive')->count(),
            'negative_evaluations' => Evaluation::where('final_result', 'negative')->count(),
            'average_practice_hours' => Evaluation::avg('practice_hours'),
            'user_evaluations' => $user->evaluations()->count(),
        ]);
    }
    
    protected function calculatePartialResults($request)
    {
        // Implémentez votre logique de calcul des résultats partiels
        // Basée sur les points du formulaire d'évaluation
        $score = 0;
        
        // Exemple de calcul simplifié
        if ($request->attitude_learning_desire) $score += 2;
        if ($request->installation === 'good') $score += 2;
        // ... etc.
        
        return $score;
    }
}
