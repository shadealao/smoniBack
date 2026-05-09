<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'instructor' => [
                'id' => $this->instructor->id,
                'name' => $this->instructor->name,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            // Section 1: Attitudes
            'attitudes' => [
                'control_priority' => (bool) $this->attitude_control_priority,
                'learning_desire' => (bool) $this->attitude_learning_desire,
            ],
            
            // Section 2: Habiletés
            'skills' => [
                'installation' => $this->installation,
                'start_stop' => $this->start_stop,
                'steering_control' => $this->steering_control,
            ],
            
            // Section 3: Compréhension et mémoire
            'cognitive_abilities' => [
                'comprehension' => $this->comprehension,
                'memory' => $this->memory,
            ],
            
            // Section 4: Perception
            'perception' => [
                'trajectory' => $this->trajectory,
                'orientation' => $this->orientation,
                'observation' => $this->observation,
                'gaze' => $this->gaze,
            ],
            
            // Section 5: Émotivité
            'emotivity' => [
                'general' => $this->emotivity,
                'tension' => $this->tension,
            ],
            
            // Section 6: Résultats
            'results' => [
                'partial_score' => (int) $this->partial_results,
                'final_result' => $this->final_result,
                'result_description' => $this->getResultDescription(),
            ],
            
            // Section 7: Proposition de formation
            'training_proposal' => [
                'theory_hours' => (int) $this->theory_hours,
                'practice_hours' => (int) $this->practice_hours,
                'gearbox_type' => $this->gearbox_type,
                'minimum_hours' => $this->gearbox_type === 'manual' ? 20 : 13,
                'proposal_accepted' => (bool) $this->proposal_accepted,
                'proposal_status' => $this->getProposalStatus(),
            ],
            
            // Liens d'actions
            'links' => [
                'self' => route('api.students.evaluations.show', [$this->student_id, $this->id]),
                'student' => route('api.students.show', $this->student_id),
            ],
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'meta' => [
                'version' => '1.0.0',
                'api_version' => 'v1',
                'copyright' => 'Auto-école API',
                'author' => 'Votre Auto-école',
            ],
        ];
    }
}
