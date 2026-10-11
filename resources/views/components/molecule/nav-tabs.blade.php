@props(['list'])

<nav class="tabs" aria-label="Resume navigation">
    @foreach ($list as $item)
        <x-atom.tab-status :status="$item['status']" :class="$item['class']" />
    @endforeach
</nav>
