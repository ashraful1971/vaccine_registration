@props(['label', 'name', 'type' => 'text', 'value', 'options' => []])
<div>
    <label class="text-gray-800 text-sm mb-2 block">{{ $label }}</label>

    @if ($type !== 'select')
        <input name="{{ $name }}" type="{{ $type }}" value="{{ $value }}"
            class="text-gray-800 bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
            placeholder="Enter {{ $name }}" />
    @else
        <select name="{{ $name }}" type="text"
            class="text-gray-800 bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500">
            @foreach ($options as $optionValue => $optionName)
                <option @selected(old($name) == $optionValue) value="{{ $optionValue }}">{{ $optionName }}</option>
            @endforeach
        </select>
    @endif

    @error($name)
        <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
    @enderror
</div>
