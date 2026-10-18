@props(['target'])

<button
    type="button"
    {{ $attributes->merge([
        'class' => 'profile-tab flex-shrink-0 d-flex justify-content-center mx-2 my-1'
    ]) }}
    data-target="{{ $target }}"
>
    {{ $slot }}
</button>
