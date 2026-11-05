@if($errors->any())
    <div style="padding: 1rem; margin-bottom: 1.5rem; background: #f8d7da; border-left: 4px solid #dc3545; color: #721c24;">
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" class="card operations-management" style="padding: 1.5rem;">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="ops-grid" style="gap: 1rem;">
        <div>
            <label for="academic_year">Academic Year</label>
            <input id="academic_year" name="academic_year" required maxlength="20" placeholder="2026/2027" value="{{ old('academic_year', $calendar?->academic_year) }}" style="width: 100%; padding: 0.65rem;">
        </div>
        <div>
            <label for="semester">Semester</label>
            <select id="semester" name="semester" required style="width: 100%; padding: 0.65rem;">
                @foreach([1, 2] as $semester)
                    <option value="{{ $semester }}" {{ old('semester', $calendar?->semester) == $semester ? 'selected' : '' }}>Semester {{ $semester }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <h2 style="margin: 1.75rem 0 0.75rem; font-size: 1.1rem;">Semester timeframe</h2>
    <div class="ops-grid" style="gap: 1rem;">
        @foreach(['semester_start_date' => 'Semester starts', 'semester_end_date' => 'Semester ends'] as $field => $label)
            <div>
                <label for="{{ $field }}">{{ $label }}</label>
                <input type="date" id="{{ $field }}" name="{{ $field }}" required value="{{ old($field, $calendar?->$field?->format('Y-m-d')) }}" style="width: 100%; padding: 0.65rem;">
            </div>
        @endforeach
    </div>

    @for($term = 1; $term <= 3; $term++)
        <h2 style="margin: 1.75rem 0 0.75rem; font-size: 1.1rem;">Term {{ $term }}</h2>
        <div class="ops-grid" style="gap: 1rem;">
            @foreach(["term_{$term}_start_date" => 'Starts', "term_{$term}_end_date" => 'Ends'] as $field => $label)
                <div>
                    <label for="{{ $field }}">Term {{ $term }} {{ strtolower($label) }}</label>
                    <input type="date" id="{{ $field }}" name="{{ $field }}" required value="{{ old($field, $calendar?->$field?->format('Y-m-d')) }}" style="width: 100%; padding: 0.65rem;">
                </div>
            @endforeach
        </div>
    @endfor

    <div class="ops-actions" style="display: flex; gap: 0.75rem; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('academic-calendar.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>