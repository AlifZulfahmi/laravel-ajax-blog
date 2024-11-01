@props([
'title' => '',
'icon' => ''
])
<div class="card">
    <div class="card-header"><i class="fa-solid fa-{{ $icon }}"></i> {{ $title }}</div>

    <div class="card-body">
        {{ $slot }}
    </div>
</div>