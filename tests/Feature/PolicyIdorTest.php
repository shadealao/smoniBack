<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\BankAccount;
use App\Models\MeetingPoint;
use App\Models\User;
use App\Models\UserDoc;
use App\Models\Vehicle;
use App\Models\Withdraw;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PolicyIdorTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_cannot_view_other_instructors_vehicle(): void
    {
        $owner = User::factory()->create(['role' => 'instructor']);
        $other = User::factory()->create(['role' => 'instructor']);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $owner->id]);

        $this->assertTrue(Gate::forUser($owner)->allows('view', $vehicle));
        $this->assertFalse(Gate::forUser($other)->allows('view', $vehicle));
        $this->assertFalse(Gate::forUser($other)->allows('update', $vehicle));
        $this->assertFalse(Gate::forUser($other)->allows('delete', $vehicle));
    }

    public function test_instructor_cannot_view_other_instructors_meeting_point(): void
    {
        $owner = User::factory()->create(['role' => 'instructor']);
        $other = User::factory()->create(['role' => 'instructor']);
        $mp = MeetingPoint::factory()->create(['instructor_id' => $owner->id]);

        $this->assertTrue(Gate::forUser($owner)->allows('view', $mp));
        $this->assertFalse(Gate::forUser($other)->allows('view', $mp));
        $this->assertFalse(Gate::forUser($other)->allows('update', $mp));
        $this->assertFalse(Gate::forUser($other)->allows('delete', $mp));
    }

    public function test_instructor_cannot_view_other_instructors_availability(): void
    {
        $owner = User::factory()->create(['role' => 'instructor']);
        $other = User::factory()->create(['role' => 'instructor']);
        $availability = Availability::factory()->create(['instructor_id' => $owner->id]);

        $this->assertTrue(Gate::forUser($owner)->allows('view', $availability));
        $this->assertFalse(Gate::forUser($other)->allows('view', $availability));
        $this->assertFalse(Gate::forUser($other)->allows('update', $availability));
        $this->assertFalse(Gate::forUser($other)->allows('delete', $availability));
    }

    public function test_instructor_cannot_view_other_instructors_bank_account(): void
    {
        $owner = User::factory()->create(['role' => 'instructor']);
        $other = User::factory()->create(['role' => 'instructor']);
        $account = BankAccount::factory()->create(['monitor_id' => $owner->id]);

        $this->assertTrue(Gate::forUser($owner)->allows('view', $account));
        $this->assertFalse(Gate::forUser($other)->allows('view', $account));
        $this->assertFalse(Gate::forUser($other)->allows('update', $account));
        $this->assertFalse(Gate::forUser($other)->allows('delete', $account));
    }

    public function test_instructor_cannot_view_other_instructors_withdraw(): void
    {
        $owner = User::factory()->create(['role' => 'instructor']);
        $other = User::factory()->create(['role' => 'instructor']);
        $withdraw = Withdraw::factory()->create(['monitor_id' => $owner->id]);

        $this->assertTrue(Gate::forUser($owner)->allows('view', $withdraw));
        $this->assertFalse(Gate::forUser($other)->allows('view', $withdraw));
        $this->assertFalse(Gate::forUser($other)->allows('update', $withdraw));
        $this->assertFalse(Gate::forUser($other)->allows('delete', $withdraw));
    }

    public function test_learner_cannot_view_other_learners_user_doc(): void
    {
        $owner = User::factory()->create(['role' => 'learner']);
        $other = User::factory()->create(['role' => 'learner']);
        $doc = UserDoc::factory()->create(['user_id' => $owner->id]);

        $this->assertTrue(Gate::forUser($owner)->allows('view', $doc));
        $this->assertFalse(Gate::forUser($other)->allows('view', $doc));
        $this->assertFalse(Gate::forUser($other)->allows('update', $doc));
        $this->assertFalse(Gate::forUser($other)->allows('delete', $doc));
    }

    public function test_third_party_cannot_view_appointment(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $thirdParty = User::factory()->create(['role' => 'learner']);
        $appointment = Appointment::factory()->create([
            'instructor_id' => $instructor->id,
            'learner_id' => $learner->id,
        ]);

        $this->assertTrue(Gate::forUser($instructor)->allows('view', $appointment));
        $this->assertTrue(Gate::forUser($learner)->allows('view', $appointment));
        $this->assertFalse(Gate::forUser($thirdParty)->allows('view', $appointment));
        $this->assertFalse(Gate::forUser($thirdParty)->allows('cancel', $appointment));
        $this->assertFalse(Gate::forUser($thirdParty)->allows('update', $appointment));
    }

    public function test_learner_cannot_update_or_delete_appointment(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $appointment = Appointment::factory()->create([
            'instructor_id' => $instructor->id,
            'learner_id' => $learner->id,
        ]);

        // Learner is a party so can view + cancel, but not update/delete.
        $this->assertTrue(Gate::forUser($learner)->allows('cancel', $appointment));
        $this->assertFalse(Gate::forUser($learner)->allows('update', $appointment));
        $this->assertFalse(Gate::forUser($learner)->allows('delete', $appointment));
    }

    public function test_admin_can_view_any_resource_via_gate_before(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);

        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        $mp = MeetingPoint::factory()->create(['instructor_id' => $instructor->id]);
        $availability = Availability::factory()->create(['instructor_id' => $instructor->id]);
        $account = BankAccount::factory()->create(['monitor_id' => $instructor->id]);
        $withdraw = Withdraw::factory()->create(['monitor_id' => $instructor->id]);
        $doc = UserDoc::factory()->create(['user_id' => $learner->id]);
        $appointment = Appointment::factory()->create([
            'instructor_id' => $instructor->id,
            'learner_id' => $learner->id,
        ]);

        foreach (['view', 'update', 'delete'] as $ability) {
            $this->assertTrue(Gate::forUser($admin)->allows($ability, $vehicle), "admin {$ability} vehicle");
            $this->assertTrue(Gate::forUser($admin)->allows($ability, $mp), "admin {$ability} meeting point");
            $this->assertTrue(Gate::forUser($admin)->allows($ability, $availability), "admin {$ability} availability");
            $this->assertTrue(Gate::forUser($admin)->allows($ability, $account), "admin {$ability} bank account");
            $this->assertTrue(Gate::forUser($admin)->allows($ability, $withdraw), "admin {$ability} withdraw");
            $this->assertTrue(Gate::forUser($admin)->allows($ability, $doc), "admin {$ability} user doc");
        }

        $this->assertTrue(Gate::forUser($admin)->allows('view', $appointment));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $appointment));
        $this->assertTrue(Gate::forUser($admin)->allows('cancel', $appointment));
        $this->assertTrue(Gate::forUser($admin)->allows('delete', $appointment));
    }
}
