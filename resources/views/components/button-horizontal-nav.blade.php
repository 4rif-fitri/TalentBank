@props(['target'])

<button
    type="button"
    {{ $attributes->merge([
        'class' => 'profile-tab flex-shrink-0 d-flex justify-content-center mt-3 mx-2'
    ]) }}
    data-target="{{ $target }}"
>
    {{ $slot }}
</button>
