<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
        ]);
        $selectedDate = $validated['date'] ?? today()->toDateString();

        $attendances = Attendance::query()
            ->with('student')
            ->whereDate('attendance_date', $selectedDate)
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('attendances.index', compact('attendances', 'selectedDate'));
    }

    public function create(): View
    {
        $students = Student::query()->orderBy('name')->get();
        $statuses = Attendance::STATUSES;

        return view('attendances.create', compact('students', 'statuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        Attendance::create($this->validatedAttendance($request));

        return redirect()->route('attendances.index')->with('success', 'Attendance record added successfully.');
    }

    public function edit(Attendance $attendance): View
    {
        $students = Student::query()->orderBy('name')->get();
        $statuses = Attendance::STATUSES;

        return view('attendances.edit', compact('attendance', 'students', 'statuses'));
    }

    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $attendance->update($this->validatedAttendance($request, $attendance));

        return redirect()->route('attendances.index', ['date' => $attendance->attendance_date->toDateString()])
            ->with('success', 'Attendance record updated.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $date = $attendance->attendance_date->toDateString();
        $attendance->delete();

        return redirect()->route('attendances.index', ['date' => $date])
            ->with('success', 'Attendance record deleted.');
    }

    private function validatedAttendance(Request $request, ?Attendance $attendance = null): array
    {
        $studentId = $request->input('student_id');
        $attendanceDateIsUnique = function (string $attribute, mixed $value, Closure $fail) use ($studentId, $attendance): void {
            if (! is_string($value) || strtotime($value) === false) {
                return;
            }

            $query = Attendance::query()
                ->where('student_id', $studentId)
                ->whereDate('attendance_date', $value);

            if ($attendance !== null) {
                $query->where($attendance->getQualifiedKeyName(), '!=', $attendance->getKey());
            }

            if ($query->exists()) {
                $fail('An attendance record already exists for this student on this date.');
            }
        };

        return $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'attendance_date' => ['required', 'date', $attendanceDateIsUnique],
            'status' => ['required', Rule::in(Attendance::STATUSES)],
        ]);
    }
}
