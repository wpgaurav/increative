{{--
CTA (Call to Action) Component

@param string $type 'default' | 'dark' | 'gradient' | 'inline' | 'bordered'
@param string $title CTA title
@param string $description CTA description
@param string $buttonText Primary button text
@param string $buttonUrl Primary button URL
@param string $buttonSecondary Secondary button text (optional)
@param string $buttonSecondaryUrl Secondary button URL (optional)
@param string $icon SVG icon content (optional)
--}}

@props([
    'type' => 'default',
    'title' => '',
    'description' => '',
    'buttonText' => '',
    'buttonUrl' => '#',
    'buttonSecondary' => '',
    'buttonSecondaryUrl' => '#',
    'icon' => ''
])

   <d   iv class="cta cta--{{ $type }}">

      @if ($icon)
        <div class="cta__icon">
              {!! $icon !!}
              </div>

     @endif
   
    @if ($type === 'inline')
                  <div
         class="cta__content">
    @endif
  
  @if ($title)
          <h2 
    class="cta__title">{{ $title }}</h2>
  @endif
  
  @if ($description)
    <p   class="cta__description">{{ $description }}</p>
  @endif

              
   @if ($type === 'inline')
     </ div>

  @endif
  
  @if ($buttonText || $buttonSecondary)

         <div class="cta__buttons">
      @if ($buttonText)
          <a href="{{ $buttonUrl }}" class="btn @if(in_array($type, ['dark', 'gradient'])) btn-action @else btn-primary @endif">
          {{ $buttonText }}
          </a>

      @endif

      @if ($buttonSecondary)
        <a href="{{ $buttonSecondaryUrl }}" class="btn @if(in_array($type, ['dark', 'gradient'])) btn-ghost @else btn-secondary @endif">
          {{ $buttonSecondary }}
        </a>
      @endif
    </div>
  @endif
  
  {{ $slot }}
</div>
