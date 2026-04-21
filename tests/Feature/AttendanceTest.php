<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $shift = Shift::create([
            'name' => 'وردية تجريبية',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'grace_minutes' => 15,
        ]);

        $dept = Department::create(['name' => 'IT', 'name_ar' => 'تقنية المعلومات']);

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
    }

    public function test_employee_can_check_in(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('employee.check-in'));

        $response->assertRedirect(route('employee.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_records', [
            'employee_id' => $this->employee->id,
            'date' => now()->toDateString(),
        ]);
    }

    public function test_employee_cannot_check_in_twice(): void
    {
        $this->actingAs($this->user)->post(route('employee.check-in'));
        $response = $this->actingAs($this->user)->post(route('employee.check-in'));

        $response->assertRedirect(route('employee.dashboard'));
        $response->assertSessionHas('error');

        $this->assertDatabaseCount('attendance_records', 1);
    }

    public function test_employee_can_check_out_after_check_in(): void
    {
        $this->actingAs($this->user)->post(route('employee.check-in'));
        $response = $this->actingAs($this->user)->post(route('employee.check-out'));

        $response->assertRedirect(route('employee.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_records', [
            'employee_id' => $this->employee->id,
            'date' => now()->toDateString(),
        ]);

        $record = $this->employee->todayAttendance;
        $this->assertNotNull($record->check_out);
    }

    public function test_employee_cannot_check_out_without_check_in(): void
    {
        $response = $this->actingAs($this->user)->post(route('employee.check-out'));

        $response->assertRedirect(route('employee.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_employee_can_view_attendance_history(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('employee.attendance.index'));

        $response->assertOk();
        $response->assertViewIs('employee.attendance.index');
    }

    public function test_employee_dashboard_accessible(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('employee.dashboard'));

        $response->assertOk();
    }

    public function test_guest_cannot_access_employee_dashboard(): void
    {
        $response = $this->get(route('employee.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertOk();
    }

    public function test_employee_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.dashboard'));

        $response->assertForbidden();
    }
}