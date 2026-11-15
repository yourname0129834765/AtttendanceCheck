@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <section class="welcome-row">
        <div>
            <h2>Attendance overview</h2>
            <p>A clear snapshot of your students and today's attendance.</p>
        </div>
        <a class="button button-primary" href="{{ route('attendances.create') }}">＋ Record attendance</a>
    </section>

    <section class="stats-grid" aria-label="Attendance summary">
        <article class="stat-card">
            <div class="stat-top"><span>Total students</span><span class="stat-icon">S</span></div>
            <strong>{{ $totalStudents }}</strong>
            <span class="stat-note">Registered students</span>
        </article>
        <article class="stat-card">
            <div class="stat-top"><span>Present today</span><span class="stat-icon">P</span></div>
            <strong>{{ $todayCounts->get('Present', 0) }}</strong>
            <span class="stat-note">Marked present</span>
        </article>
        <article class="stat-card">
            <div class="stat-top"><span>Absent today</span><span class="stat-icon">A</span></div>
            <strong>{{ $todayCounts->get('Absent', 0) }}</strong>
            <span class="stat-note">Marked absent</span>
        </article>
        <article class="stat-card">
            <div class="stat-top"><span>Late today</span><span class="stat-icon">L</span></div>
            <strong>{{ $todayCounts->get('Late', 0) }}</strong>
            <span class="stat-note">Arrived late</span>
        </article>
    </section>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Recent attendance</h2>
                <p>Latest attendance records across all dates.</p>
            </div>
            <a class="text-link" href="{{ route('attendances.index') }}">View all <span aria-hidden="true">→</span></a>
        </div>
        @if ($recentAttendances->isEmpty())
            <div class="empty-state">
                <div class="empty-mark">A</div>
                <h3>No attendance records yet</h3>
                <p>Add students and record their attendance to see activity here.</p>
                <a class="button button-secondary" href="{{ route('students.create') }}">Add your first student</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Student</th><th>Student no.</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($recentAttendances as $attendance)
                            <tr>
                                <td><span class="person-name">{{ $attendance->student->name }}</span></td>
                                <td>{{ $attendance->student->student_number }}</td>
                                <td>{{ $attendance->attendance_date->format('M j, Y') }}</td>
                                <td><span class="status-badge status-{{ strtolower($attendance->status) }}">{{ $attendance->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
