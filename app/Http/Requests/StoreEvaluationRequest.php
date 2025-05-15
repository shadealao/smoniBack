<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEvaluationRequest extends FormRequest
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
            'attitude_control_priority' => 'required|boolean',
            'attitude_learning_desire' => 'required|boolean',
            
            // Habiletés
            'installation' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'start_stop' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'steering_control' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            
            // Compréhension et mémoire
            'comprehension' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'memory' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            
            // Perception
            'trajectory' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'orientation' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'observation' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'gaze' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            
            // Émotivité
            'emotivity' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            'tension' => [
                'required',
                Rule::in(['weak', 'satisfactory', 'good']),
            ],
            
            // Résultats
            'final_result' => [
                'required',
                Rule::in(['positive', 'negative']),
            ],
            
            // Proposition de formation
            'theory_hours' => 'required|integer|min:0|max:50',
            'practice_hours' => 'required|integer|min:13|max:100',
            'gearbox_type' => [
                'required',
                Rule::in(['manual', 'automatic']),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'in' => 'La valeur sélectionnée pour :attribute est invalide.',
            'boolean' => 'Le champ :attribute doit être vrai ou faux.',
            'integer' => 'Le champ :attribute doit être un nombre entier.',
            'min' => [
                'numeric' => 'La valeur de :attribute doit être au moins :min.',
            ],
            'max' => [
                'numeric' => 'La valeur de :attribute ne peut pas dépasser :max.',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'attitude_control_priority' => 'priorité de contrôle',
            'attitude_learning_desire' => 'désir d\'apprentissage',
            'installation' => 'installation au poste de conduite',
            'start_stop' => 'démarrage/arrêt',
            'steering_control' => 'maniement du volant',
            'comprehension' => 'compréhension',
            'memory' => 'mémoire',
            'trajectory' => 'trajectoire',
            'orientation' => 'orientation',
            'observation' => 'observation',
            'gaze' => 'regard',
            'emotivity' => 'émotivité générale',
            'tension' => 'crispation',
            'final_result' => 'résultat final',
            'theory_hours' => 'heures de théorie',
            'practice_hours' => 'heures de pratique',
            'gearbox_type' => 'type de boîte de vitesse',
        ];
    }
}
