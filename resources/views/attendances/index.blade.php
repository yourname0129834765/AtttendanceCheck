@extends('layouts.app')

@section('page-title', 'Attendance')

@section('content')
    <section class="page-intro">
        <div>
            <h2>Attendance records</h2>
            <p>Review and update student attendance by date.</p>
        </div>
        <a class="button button-primary" href="{{ route('attendances.create') }}">＋ Record attendance</a>
    </section>

    <section class="panel">
        <form class="toolbar" method="GET" action="{{ route('attendances.index') }}">
            <label class="date-field">
                <span class="field-label">Attendance date</span>
                <input type="date" name="date" value="{{ $selectedDate }}">
            </label>
            <button class="button button-secondary" type="submit">Show records</button>
            <span class="result-count">{{ $attendances->total() }} {{ Str::plural('record', $attendances->total()) }}</span>
        </form>

        @if ($attendances->isEmpty())
            <div class="empty-state">
                <div class="empty-mark">A</div>
                <h3>No attendance for this date</h3>
                <p>Choose another date or add a new attendance record.</p>
                <a class="button button-primary" href="{{ route('attendances.create') }}">Record attendance</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Student</th><th>Student number</th><th>Course</th><th>Date</th><th>Status</th><th class="actions-heading">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td><span class="person-name">{{ $attendance->student->name }}</span></td>
                                <td>{{ $attendance->student->student_number }}</td>
                                <td>{{ $attendance->student->course }}</td>
                                <td>{{ $attendance->attendance_date->format('M j, Y') }}</td>
                                <td><span class="status-badge status-{{ strtolower($attendance->status) }}">{{ $attendance->status }}</span></td>
                                <td class="actions-cell">
                                    <a class="action-link" href="{{ route('attendances.edit', $attendance) }}">Edit</a>
                                    <form method="POST" action="{{ route('attendances.destroy', $attendance) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-link action-delete" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $attendances->links() }}</div>
        @endif
    </section>
@endsection
