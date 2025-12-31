{{--
  Social Share Component
  
  @param string $url URL to share (defaults to current URL)
  @param string $title Title for share (defaults to post title)
  @param string $type 'inline' | 'floating' | 'bottom'
  @param array $networks Networks to show
--}}

@props([
  'url' => '',
  'title' => '',
  'type' => 'inline',
  'networks' => ['twitter', 'facebook', 'linkedin', 'whatsapp', 'email', 'copy']
])

@php
  $url = $url ?: get_permalink();
  $title = $title ?: get_the_title();
  $encoded_url = urlencode($url);
  $encoded_title = urlencode($title);
  
  $share_links = [
    'twitter' => [
      'url' => "https://twitter.com/intent/tweet?url={$encoded_url}&text={$encoded_title}",
      'label' => 'Twitter',
      'icon' => '<path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>',
    ],
    'facebook' => [
      'url' => "https://www.facebook.com/sharer/sharer.php?u={$encoded_url}",
      'label' => 'Facebook',
      'icon' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
    ],
    'linkedin' => [
      'url' => "https://www.linkedin.com/shareArticle?mini=true&url={$encoded_url}&title={$encoded_title}",
      'label' => 'LinkedIn',
      'icon' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
    ],
    'whatsapp' => [
      'url' => "https://api.whatsapp.com/send?text={$encoded_title}%20{$encoded_url}",
      'label' => 'WhatsApp',
      'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>',
    ],
    'telegram' => [
      'url' => "https://t.me/share/url?url={$encoded_url}&text={$encoded_title}",
      'label' => 'Telegram',
      'icon' => '<path d="M21.198 2.433a2.242 2.242 0 0 0-1.022.215l-16.5 7.5a2.25 2.25 0 0 0 .126 4.073l3.9 1.205 1.272 4.082a2.25 2.25 0 0 0 3.6.897l2.133-2.133 3.885 2.85a2.25 2.25 0 0 0 3.502-1.36l3-13.5a2.25 2.25 0 0 0-2.896-2.83z"/>',
    ],
    'reddit' => [
      'url' => "https://reddit.com/submit?url={$encoded_url}&title={$encoded_title}",
      'label' => 'Reddit',
      'icon' => '<circle cx="16.5" cy="13.5" r="1.5"/><circle cx="7.5" cy="13.5" r="1.5"/><path d="M12 17c-2.5 0-4-1.5-4-1.5"/><circle cx="12" cy="12" r="10"/><path d="M18 8c0-1.1-.9-2-2-2s-2 .9-2 2"/>',
    ],
    'email' => [
      'url' => "mailto:?subject={$encoded_title}&body={$encoded_url}",
      'label' => 'Email',
      'icon' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
    ],
    'copy' => [
      'url' => $url,
      'label' => 'Copy Link',
      'icon' => '<rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>',
    ],
  ];
@endphp

<div class="share share--{{ $type }}" data-share-url="{{ $url }}">
  @if ($type === 'inline')
    <span class="share__label">{{ __('Share', 'sage') }}</span>
  @endif
  
  <div class="share__buttons">
    @foreach ($networks as $network)
      @if (isset($share_links[$network]))
        @php $link = $share_links[$network]; @endphp
        
        @if ($network === 'copy')
          <button 
            class="share__btn share__btn--{{ $network }}"
            data-share-copy
            aria-label="{{ $link['label'] }}"
            title="{{ $link['label'] }}"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $link['icon'] !!}</svg>
          </button>
        @else
          <a 
            href="{{ $link['url'] }}"
            class="share__btn share__btn--{{ $network }}"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="{{ sprintf(__('Share on %s', 'sage'), $link['label']) }}"
            title="{{ $link['label'] }}"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $link['icon'] !!}</svg>
          </a>
        @endif
      @endif
    @endforeach
    
    {{-- Native Share Button (Mobile) --}}
    <button 
      class="share__btn share__native"
      data-share-native
      data-url="{{ $url }}"
      data-title="{{ $title }}"
      aria-label="{{ __('Share', 'sage') }}"
    >
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="18" cy="5" r="3"/>
        <circle cx="6" cy="12" r="3"/>
        <circle cx="18" cy="19" r="3"/>
        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
      </svg>
    </button>
  </div>
</div>
