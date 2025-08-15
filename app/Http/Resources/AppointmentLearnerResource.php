<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\ExamRegistration;
use App\Models\LearnerProfile;
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
        $info = LearnerProfile::where('user_id',$this->learner_id)->orderBy('id','desc')->first();
        if($exam)
            $status = $exam->status == "registered" ? true : false;
        else $status = false;
        return [
            'id' => $this->learner->id,
            'learner' => $this->learner,
            'total_duration' => $this->total_duration/60,
            'hour_estimate' => $info->hour,
            'status' => $status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
