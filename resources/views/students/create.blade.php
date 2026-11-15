@extends('layouts.app')

@section('page-title', 'Add student')

@section('content')
    <section class="page-intro">
        <div>
            <h2>Add a student</h2>
            <p>Enter the student's information below.</p>
        </div>
        <a class="text-link" href="{{ route('students.index') }}">← Back to students</a>
    </section>
    <section class="panel form-panel">
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            @include('students._form', ['submitLabel' => 'Save student'])
        </form>
    </section>
@endsection
