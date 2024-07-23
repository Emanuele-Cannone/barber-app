@props(['selected', 'modelId'])
<div>
    <select wire:change="roleChanged($event.target.value, {{ $modelId }})">
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
