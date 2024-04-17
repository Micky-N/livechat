@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-neutral-700 bg-neutral-900 text-neutral-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-sm']) !!}>
