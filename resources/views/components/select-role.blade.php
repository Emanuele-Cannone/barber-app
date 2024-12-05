@props(['selected', 'modelId', 'readonly'])
<div>
    <select wire:change="roleChanged($event.target.value, {{ $modelId }})" {{ \App\Models\User::find($modelId)->hasRole('Super-Admin') ? 'disabled' : '' }}>
        @foreach ($options as $option)
            <option
                value="{{ $option['id'] }}"
                @if ($option['id'] == $selected)
                    selected="selected"
                @endif
            >
                {{ $option['name'] }}
            </option>
        @endforeach

    </select>
</div>
