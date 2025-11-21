@props(['key', 'size' => 'sm', 'class' => ''])

<button type="button" 
        class="btn btn-link p-0 text-muted {{ $class }} help-icon-btn" 
        data-help-key="{{ $key }}"
        title="Ajuda"
        style="text-decoration: none; vertical-align: middle;">
    <i class="bi bi-question-circle{{ $size === 'lg' ? '-fill' : '' }}" style="font-size: {{ $size === 'lg' ? '1.2rem' : '1rem' }};"></i>
</button>

