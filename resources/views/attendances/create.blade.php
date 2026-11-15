@extends('layouts.app')

@section('page-title', 'Record attendance')

@section('content')
    <section class="page-intro">
        <div>
            <h2>Record attendance</h2>
            <p>Choose a student, date, and attendance status.</p>
        </div>
        <a class="text-link" href="{{ route('attendances.index') }}">← Back to attendance</a>
    </section>
    <section class="panel form-panel">
        <form method="POST" action="{{ route('attendances.store') }}">
            @csrf
            @include('attendances._form', ['submitLabel' => 'Save record'])
        </form>
    </section>
@endsection
