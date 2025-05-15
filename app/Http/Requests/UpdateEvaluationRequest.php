<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Attitudes
            'attitude_control_priority' => 'sometimes|boolean',
            'attitude_learning_desire' => 'sometimes|boolean',
            
            // Habiletés
            'installation' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'start_stop' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'steering_control' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            
            // Compréhension et mémoire
            'comprehension' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'memory' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            
            // Perception
            'trajectory' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'orientation' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'observation' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'gaze' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            
            // Émotivité
            'emotivity' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'tension' => [
                'sometimes',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            
            // Résultats
            'final_result' => [
                'sometimes',
                Rule::in(['positive', 'negative']),
            ],
            
            // Proposition de formation
            'theory_hours' => 'sometimes|integer|min:0|max:50',
            'practice_hours' => 'sometimes|integer|min:13|max:100',
            'gearbox_type' => [
                'sometimes',
                Rule::in(['manual', 'automatic']),
            ],
            'proposal_accepted' => 'sometimes|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('proposal_accepted')) {
            $this->merge([
                'proposal_accepted' => filter_var($this->proposal_accepted, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return (new StoreEvaluationRequest())->messages();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return (new StoreEvaluationRequest())->attributes();
    }
}
