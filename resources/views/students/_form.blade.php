<div class="form-grid">
    <div class="form-field">
        <label for="student_number">Student number</label>
        <input id="student_number" name="student_number" value="{{ old('student_number', $student->student_number ?? '') }}" maxlength="50" required>
        @error('student_number') <span class="field-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-field">
        <label for="name">Student name</label>
        <input id="name" name="name" value="{{ old('name', $student->name ?? '') }}" maxlength="255" required>
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-field">
        <label for="course">Course</label>
        <input id="course" name="course" value="{{ old('course', $student->course ?? '') }}" maxlength="120" placeholder="e.g. BS Information Technology" required>
        @error('course') <span class="field-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-field">
        <label for="year_level">Year level</label>
        <select id="year_level" name="year_level" required>
            <option value="">Select year level</option>
            @foreach (range(1, 6) as $year)
                <option value="{{ $year }}" @selected((string) old('year_level', $student->year_level ?? '') === (string) $year)>Year {{ $year }}</option>
            @endforeach
        </select>
        @error('year_level') <span class="field-error">{{ $message }}</span> @enderror
    </div>
</div>
<div class="form-actions">
    <a class="button button-secondary" href="{{ route('students.index') }}">Cancel</a>
    <button class="button button-primary" type="submit">{{ $submitLabel }}</button>
</div>
