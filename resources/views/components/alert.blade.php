@props([
  'type' => 'info',
  'dismissible' => false,
])

@php
  $classes = match($type) {
    'success' => 'bg-green-50 text-green-800 border-green-200',
    'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
    'error' => 'bg-red-50 text-red-800 border-red-200',
    default => 'bg-blue-50 text-blue-800 border-blue-200',
  };
@endphp

<div 
  class="p-4 rounded-lg border {{ $classes }}" 
  role="alert"
  @if($dismissible) x-data="{ show: true }" x-show="show" @endif
>
  <div class="flex items-start gap-3">
    <div class="flex-1">
      {{ $slot }}
    </div>
    @if($dismissible)
      <button 
        type="button" 
        class="text-current opacity-50 hover:opacity-100"
        @click="show = false"
        aria-label="{{ __('Dismiss', 'sage') }}"
      >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    @endif
  </div>
</div>
