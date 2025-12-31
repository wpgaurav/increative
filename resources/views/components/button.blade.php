@props([
  'variant' => 'primary',
  'size' => 'md',
  'href' => null,
  'type' => 'button',
])

@php
  $baseClasses = 'btn';
  
  $variantClasses = match($variant) {
    'primary' => 'btn-primary',
    'secondary' => 'btn-secondary',
    'ghost' => 'btn-ghost',
    'link' => 'btn-link',
    default => 'btn-primary',
  };
  
  $sizeClasses = match($size) {
    'sm' => 'btn-sm',
    'lg' => 'btn-lg',
    default => '',
  };
  
  $classes = implode(' ', array_filter([$baseClasses, $variantClasses, $sizeClasses, $attributes->get('class')]));
@endphp

@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
  </a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
  </button>
@endif
