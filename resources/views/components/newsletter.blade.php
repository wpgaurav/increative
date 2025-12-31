{{--
  Newsletter Component
  
  @param string $type 'hero' | 'inline' | 'minimal'
  @param string $title Custom title
  @param string $description Custom description
  @param string $action Form action URL
--}}

@props([
  'type' => 'hero',
  'title' => '',
  'description' => '',
  'action' => '#'
])

@php
  $title = $title ?: __('Subscribe to our newsletter', 'sage');
  $description = $description ?: __('Get the latest posts delivered right to your inbox. No spam, unsubscribe anytime.', 'sage');
@endphp

<div class="newsletter newsletter--{{ $type }}">
  @if ($type === 'hero')
    <svg class="newsletter__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
      <polyline points="22,6 12,13 2,6"/>
    </svg>
  @endif
  
  @if ($type !== 'inline')
    <h2 class="newsletter__title">{{ $title }}</h2>
    <p class="newsletter__description">{{ $description }}</p>
  @else
    <div class="newsletter__content">
      <h2 class="newsletter__title">{{ $title }}</h2>
      <p class="newsletter__description">{{ $description }}</p>
    </div>
  @endif
  
  <form class="newsletter__form" action="{{ $action }}" method="POST" data-newsletter-form>
    @csrf
    
    <input 
      type="email" 
      name="email" 
      class="newsletter__input" 
      placeholder="{{ __('Enter your email', 'sage') }}"
      required
      aria-label="{{ __('Email address', 'sage') }}"
    >
    
    <button type="submit" class="newsletter__submit">
      {{ __('Subscribe', 'sage') }}
    </button>
  </form>
  
  @if ($type === 'hero')
    <p class="newsletter__privacy">
      {{ __('We respect your privacy.', 'sage') }}
      <a href="{{ get_privacy_policy_url() }}">{{ __('Privacy Policy', 'sage') }}</a>
    </p>
  @endif
  
  {{-- Success Message (hidden by default) --}}
  <div class="newsletter__success" style="display: none;" data-newsletter-success>
    <svg class="newsletter__success-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
      <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <h3 class="newsletter__success-title">{{ __('You\'re subscribed!', 'sage') }}</h3>
    <p class="newsletter__success-text">{{ __('Thank you for subscribing. Check your email to confirm.', 'sage') }}</p>
  </div>
</div>
