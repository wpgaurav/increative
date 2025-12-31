{{-- Pagination Partial --}}

@php
    global $wp_query;
    $query = $query ?? $wp_query;
    $type = $type ?? 'numbered';
@endphp

<x-pagination :query="$query" :type="$type" />