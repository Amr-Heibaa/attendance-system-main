<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveRequestTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Employee $employee;
    protected LeaveType $leaveType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $dept = Department::create(['name' => 'IT', 'name_ar' => 'تقنية المعلومات']);
        $shift = Shift::create(['name' => 'صباحي', 'start_time' => '08:00', 'end_time' => '16:00', 'grace_minutes' => 15]);

        $this->user = User::factory()->create();
        $this->user->assignRole('employee');

        $this->employee = Employee::create([
            'user_id' => $this->user->id,
            'employee_code' => 'TEST001',
            'department_id' => $dept->id,
            'shift_id' => $shift->id,
            'hire_date' => now()->subYear(),
            'status' => 'active',
        ]);

        $this->leaveType = LeaveType::create([
            'name' => 'Annual', 'name_ar' => 'سنوية',
            'max_days_per_year' => 21, 'is_paid' => true,
        ]);
    }

    public function test_employee_can_submit_leave_request(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('employee.leaves.store'), [
                'leave_type_id' => $this->leaveType->id,
                'start_date'    => now()->addDays(5)->toDateString(),
                'end_date'      => now()->addDays(7)->toDateString(),
                'reason'        => 'سفر لزيارة الأهل في العيد',
            ]);

        $response->assertRedirect(route('employee.leaves.index'));
        $this->assertDatabaseHas('leave_requests', [
            'employee_id' => $this->employee->id,
            'status' => 'pending',
        ]);
    }

    public function test_leave_request_requires_reason(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('employee.leaves.store'), [
                'leave_type_id' => $this->leaveType->id,
                'start_date'    => now()->addDays(5)->toDateString(),
                'end_date'      => now()->addDays(7)->toDateString(),
                'reason'        => '',
            ]);

        $response->assertSessionHasErrors('reason');
    }

    public function test_admin_can_approve_leave_request(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $leaveRequest = \App\Models\LeaveRequest::create([
            'employee_id'   => $this->employee->id,
            'leave_type_id' => $this->leaveType->id,
            'start_date'    => now()->addDays(5)->toDateString(),
            'end_date'      => now()->addDays(7)->toDateString(),
            'days_count'    => 3,
            'reason'        => 'إجازة سنوية',
            'status'        => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.leave-requests.approve', $leaveRequest), [
                'manager_notes' => 'تم القبول',
            ]);

        $response->assertRedirect(route('admin.leave-requests.index'));
        $leaveRequest->refresh();
        $this->assertEquals('approved', $leaveRequest->status);
    }
}