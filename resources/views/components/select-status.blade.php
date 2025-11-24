<div>
    <select
        wire:change="$dispatch('statusChanged', { status: $event.target.value, id: '{{ $rowId }}' })"
        class="form-select form-select-sm border-gray-300 rounded">
        @foreach($options as $key => $label)
            <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
