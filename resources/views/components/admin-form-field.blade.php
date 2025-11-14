@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'help' => '',
    'options' => [],
    'selected' => null,
    'multiple' => false,
    'accept' => '',
    'min' => '',
    'max' => '',
    'step' => '',
    'rows' => 3,
    'cols' => 50
])

@php
    $fieldId = 'field_' . $name;
    $oldValue = old($name, $value);
    $hasError = $errors->has($name);
    $errorClass = $hasError ? 'is-invalid' : '';
    $successClass = !$hasError && $oldValue ? 'is-valid' : '';
@endphp

<div class="mb-4">
    <label for="{{ $fieldId }}" class="form-label fw-semibold text-dark">
        @if($required)
            <span class="text-danger">*</span>
        @endif
        <i class="fas fa-{{ $type === 'email' ? 'envelope' : ($type === 'password' ? 'lock' : ($type === 'file' ? 'file-upload' : 'edit')) }} me-2 text-primary"></i>
        {{ $label }}
    </label>

    @switch($type)
        @case('textarea')
            <textarea 
                id="{{ $fieldId }}"
                name="{{ $name }}"
                class="form-control form-control-lg {{ $errorClass }} {{ $successClass }}"
                placeholder="{{ $placeholder }}"
                rows="{{ $rows }}"
                cols="{{ $cols }}"
                {{ $required ? 'required' : '' }}
                {{ $attributes->merge(['class' => '']) }}
            >{{ $oldValue }}</textarea>
            @break

        @case('select')
            <select 
                id="{{ $fieldId }}"
                name="{{ $name }}"
                class="form-select form-select-lg {{ $errorClass }} {{ $successClass }}"
                {{ $required ? 'required' : '' }}
                {{ $multiple ? 'multiple' : '' }}
                {{ $attributes->merge(['class' => '']) }}
            >
                <option value="">Select {{ $label }}</option>
                @foreach((is_array($options) ? $options : []) as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" 
                        {{ (is_array($selected) ? in_array($optionValue, $selected) : $selected == $optionValue) ? 'selected' : '' }}>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
            @break

        @case('file')
            <input 
                type="file"
                id="{{ $fieldId }}"
                name="{{ $name }}"
                class="form-control form-control-lg {{ $errorClass }} {{ $successClass }}"
                {{ $required ? 'required' : '' }}
                {{ $multiple ? 'multiple' : '' }}
                {{ $accept ? 'accept=' . $accept : '' }}
                {{ $attributes->merge(['class' => '']) }}
            >
            @if($oldValue && !is_string($oldValue))
                <div class="mt-2">
                    <small class="text-muted">Current file: {{ $oldValue->getClientOriginalName() }}</small>
                </div>
            @endif
            @break

        @case('checkbox')
        @case('radio')
            <div class="form-check-group">
                @foreach((is_array($options) ? $options : []) as $optionValue => $optionLabel)
                    <div class="form-check">
                        <input 
                            type="{{ $type }}"
                            id="{{ $fieldId }}_{{ $optionValue }}"
                            name="{{ $name }}"
                            value="{{ $optionValue }}"
                            class="form-check-input {{ $errorClass }}"
                            {{ (is_array($oldValue) ? in_array($optionValue, $oldValue) : $oldValue == $optionValue) ? 'checked' : '' }}
                            {{ $required ? 'required' : '' }}
                            {{ $attributes->merge(['class' => '']) }}
                        >
                        <label class="form-check-label" for="{{ $fieldId }}_{{ $optionValue }}">
                            {{ $optionLabel }}
                        </label>
                    </div>
                @endforeach
            </div>
            @break

        @case('date')
        @case('datetime-local')
        @case('time')
            <input 
                type="{{ $type }}"
                id="{{ $fieldId }}"
                name="{{ $name }}"
                class="form-control form-control-lg {{ $errorClass }} {{ $successClass }}"
                value="{{ $oldValue }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $min ? 'min=' . $min : '' }}
                {{ $max ? 'max=' . $max : '' }}
                {{ $attributes->merge(['class' => '']) }}
            >
            @break

        @case('number')
            <input 
                type="number"
                id="{{ $fieldId }}"
                name="{{ $name }}"
                class="form-control form-control-lg {{ $errorClass }} {{ $successClass }}"
                value="{{ $oldValue }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $min ? 'min=' . $min : '' }}
                {{ $max ? 'max=' . $max : '' }}
                {{ $step ? 'step=' . $step : '' }}
                {{ $attributes->merge(['class' => '']) }}
            >
            @break

        @default
            <input 
                type="{{ $type }}"
                id="{{ $fieldId }}"
                name="{{ $name }}"
                class="form-control form-control-lg {{ $errorClass }} {{ $successClass }}"
                value="{{ $oldValue }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $attributes->merge(['class' => '']) }}
            >
    @endswitch

    @if($help)
        <div class="form-text text-muted">
            <i class="fas fa-info-circle me-1"></i>
            {{ $help }}
        </div>
    @endif

    @if($hasError)
        <div class="invalid-feedback d-block">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ $errors->first($name) }}
        </div>
    @endif

    @if(!$hasError && $oldValue)
        <div class="valid-feedback d-block">
            <i class="fas fa-check-circle me-1"></i>
            Field looks good!
        </div>
    @endif
</div>
