<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\ExamRegistration;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentLearnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $exam = ExamRegistration::where('learner_id',$this->learner_id)->orderBy('id','desc')->first();
        if($exam)
            $status = $exam->status == "registered" ? true : false;
        else $status = false;
        return [
            'id' => $this->id,
            'learner' => $this->learner,
            'total_duration' => $this->total_duration,
            'status' => $status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            
        ];
    }
}
