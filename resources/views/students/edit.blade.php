@extends('layouts.app')

@section('page-title', 'Edit student')

@section('content')
    <section class="page-intro">
        <div>
            <h2>Edit student</h2>
            <p>Update {{ $student->name }}'s information.</p>
        </div>
        <a class="text-link" href="{{ route('students.index') }}">← Back to students</a>
    </section>
    <section class="panel form-panel">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf
            @method('PUT')
            @include('students._form', ['submitLabel' => 'Save changes'])
        </form>
    </section>
@endsection
