<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_student_and_attendance_totals_for_today(): void
    {
        $this->travelTo(now()->setDate(2025, 6, 10)->setTime(8, 0));
        $presentStudent = Student::factory()->create();
        $absentStudent = Student::factory()->create();

        Attendance::factory()->for($presentStudent)->create([
            'attendance_date' => today()->toDateString(),
            'status' => 'Present',
        ]);
        Attendance::factory()->for($absentStudent)->create([
            'attendance_date' => today()->toDateString(),
            'status' => 'Absent',
        ]);
        Attendance::factory()->for($presentStudent)->create([
            'attendance_date' => today()->subDay()->toDateString(),
            'status' => 'Late',
        ]);

        $response = $this->get(route('dashboard'));

        $response->assertOk()
            ->assertViewHas('totalStudents', 2)
            ->assertViewHas('todayCounts', function ($counts): bool {
                return (int) $counts->get('Present', 0) === 1
                    && (int) $counts->get('Absent', 0) === 1
                    && (int) $counts->get('Late', 0) === 0;
            });
    }

    public function test_student_can_be_created_updated_and_deleted(): void
    {
        $studentData = [
            'student_number' => '202600123',
            'name' => 'Alex Rivera',
            'course' => 'BS Information Technology',
            'year_level' => 2,
        ];

        $this->post(route('students.store'), $studentData)
            ->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', $studentData);

        $student = Student::query()->where('student_number', '202600123')->firstOrFail();
        $updatedData = [
            'student_number' => '202600123',
            'name' => 'Alex Santos',
            'course' => 'BS Computer Science',
            'year_level' => 3,
        ];

        $this->put(route('students.update', $student), $updatedData)
            ->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', $updatedData);

        $this->delete(route('students.destroy', $student))
            ->assertRedirect(route('students.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_duplicate_student_number_is_rejected(): void
    {
        Student::factory()->create(['student_number' => '202600123']);

        $this->post(route('students.store'), [
            'student_number' => '202600123',
            'name' => 'Alex Rivera',
            'course' => 'BS Information Technology',
            'year_level' => 2,
        ])
            ->assertSessionHasErrors('student_number');

        $this->assertDatabaseCount('students', 1);
    }

    public function test_attendance_can_be_created_updated_and_deleted(): void
    {
        $this->travelTo(now()->setDate(2025, 6, 10)->setTime(8, 0));
        $student = Student::factory()->create();
        $attendanceData = [
            'student_id' => $student->id,
            'attendance_date' => today()->toDateString(),
            'status' => 'Present',
        ];

        $this->post(route('attendances.store'), $attendanceData)
            ->assertRedirect(route('attendances.index'));
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'status' => 'Present',
        ]);

        $attendance = Attendance::query()->firstOrFail();
        $this->assertSame(today()->toDateString(), $attendance->attendance_date->toDateString());
        $updatedData = [
            'student_id' => $student->id,
            'attendance_date' => today()->toDateString(),
            'status' => 'Late',
        ];

        $this->put(route('attendances.update', $attendance), $updatedData)
            ->assertRedirect(route('attendances.index', ['date' => today()->toDateString()]));
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'status' => 'Late',
        ]);

        $this->delete(route('attendances.destroy', $attendance))
            ->assertRedirect(route('attendances.index', ['date' => today()->toDateString()]));
        $this->assertDatabaseMissing('attendances', ['id' => $attendance->id]);
    }

    public function test_duplicate_attendance_for_a_student_and_date_is_rejected(): void
    {
        $student = Student::factory()->create();
        Attendance::factory()->for($student)->create([
            'attendance_date' => '2025-06-10',
        ]);

        $this->post(route('attendances.store'), [
            'student_id' => $student->id,
            'attendance_date' => '2025-06-10',
            'status' => 'Absent',
        ])
            ->assertSessionHasErrors('attendance_date');

        $this->assertDatabaseCount('attendances', 1);
    }

    public function test_attendance_list_only_shows_records_for_the_selected_date(): void
    {
        $student = Student::factory()->create(['name' => 'Today Student']);
        $otherStudent = Student::factory()->create(['name' => 'Earlier Student']);
        Attendance::factory()->for($student)->create(['attendance_date' => '2025-06-10']);
        Attendance::factory()->for($otherStudent)->create(['attendance_date' => '2025-06-09']);

        $this->get(route('attendances.index', ['date' => '2025-06-10']))
            ->assertOk()
            ->assertSee('Today Student')
            ->assertDontSee('Earlier Student');
    }
}
