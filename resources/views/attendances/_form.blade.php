@if ($students->isEmpty())
    <div class="empty-state form-empty">
        <div class="empty-mark">S</div>
        <h3>Add a student first</h3>
        <p>Attendance records need to be linked to a student profile.</p>
        <a class="button button-primary" href="{{ route('students.create') }}">Add student</a>
    </div>
@else
    <div class="form-grid">
        <div class="form-field">
            <label for="student_id">Student</label>
            <select id="student_id" name="student_id" required>
                <option value="">Select a student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected((string) old('student_id', $attendance->student_id ?? '') === (string) $student->id)>
                        {{ $student->name }} · {{ $student->student_number }}
                    </option>
                @endforeach
            </select>
            @error('student_id') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
            <label for="attendance_date">Attendance date</label>
            <input id="attendance_date" type="date" name="attendance_date" value="{{ old('attendance_date', isset($attendance) ? $attendance->attendance_date->toDateString() : today()->toDateString()) }}" required>
            @error('attendance_date') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
            <label for="status">Attendance status</label>
            <select id="status" name="status" required>
                <option value="">Select a status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $attendance->status ?? '') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            @error('status') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="form-actions">
        <a class="button button-secondary" href="{{ route('attendances.index') }}">Cancel</a>
        <button class="button button-primary" type="submit">{{ $submitLabel }}</button>
    </div>
@endif
