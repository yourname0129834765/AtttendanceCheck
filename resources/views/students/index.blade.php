@extends('layouts.app')

@section('page-title', 'Students')

@section('content')
    <section class="page-intro">
        <div>
            <h2>Student directory</h2>
            <p>Manage student profiles, courses, and year levels.</p>
        </div>
        <a class="button button-primary" href="{{ route('students.create') }}">＋ Add student</a>
    </section>

    <section class="panel">
        <form class="toolbar" method="GET" action="{{ route('students.index') }}">
            <label class="search-field">
                <span class="visually-hidden">Search students</span>
                <input type="search" name="search" value="{{ $search }}" placeholder="Search name, number, or course">
            </label>
            <button class="button button-secondary" type="submit">Search</button>
            @if ($search !== '')
                <a class="text-link" href="{{ route('students.index') }}">Clear</a>
            @endif
            <span class="result-count">{{ $students->total() }} {{ Str::plural('student', $students->total()) }}</span>
        </form>

        @if ($students->isEmpty())
            <div class="empty-state">
                <div class="empty-mark">S</div>
                <h3>{{ $search !== '' ? 'No matching students' : 'No students added yet' }}</h3>
                <p>{{ $search !== '' ? 'Try another name, student number, or course.' : 'Add your first student to get started.' }}</p>
                @if ($search === '')
                    <a class="button button-primary" href="{{ route('students.create') }}">Add student</a>
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Student</th><th>Student number</th><th>Course</th><th>Year level</th><th class="actions-heading">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>
                                    <div class="student-cell">
                                        <span class="avatar">{{ Str::upper(Str::substr($student->name, 0, 1)) }}</span>
                                        <span class="person-name">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $student->student_number }}</td>
                                <td>{{ $student->course }}</td>
                                <td><span class="level-pill">Year {{ $student->year_level }}</span></td>
                                <td class="actions-cell">
                                    <a class="action-link" href="{{ route('students.edit', $student) }}">Edit</a>
                                    <form method="POST" action="{{ route('students.destroy', $student) }}">
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
            <div class="pagination-wrap">{{ $students->links() }}</div>
        @endif
    </section>
@endsection
