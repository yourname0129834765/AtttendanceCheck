@extends('layouts.app')

@section('page-title', 'Edit attendance')

@section('content')
    <section class="page-intro">
        <div>
            <h2>Edit attendance</h2>
            <p>Update the student, date, or status for this record.</p>
        </div>
        <a class="text-link" href="{{ route('attendances.index', ['date' => $attendance->attendance_date->toDateString()]) }}">← Back to attendance</a>
    </section>
    <section class="panel form-panel">
        <form method="POST" action="{{ route('attendances.update', $attendance) }}">
            @csrf
            @method('PUT')
            @include('attendances._form', ['submitLabel' => 'Save changes'])
        </form>
    </section>
@endsection
