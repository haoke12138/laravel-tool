<?php $current = get_current_page($slug)?>

@if (last(explode('.', $slug)) == 'detail' && !empty($article))  {{-- 详情页--}}
    <?php $title = $current['tdk']['TITLE'] ?? config('app.name')?>
    <?php $keyword = $current['tdk']['TITLE'] ?? config('app.keywords')?>
    <?php $desc = $current['tdk']['TITLE'] ?? config('app.desc')?>

    <title>{{ $article['tdk']['TITLE'] ?? $title }}</title>
    <meta name="Keywords" content="{{ $article['tdk']['KEYWORDS'] ?? $keyword }}">
    <meta name="Description" content="{{ $article['tdk']['DESC'] ?? $desc }}">
@else
    <title>{{ $current['tdk']['TITLE'] ?? config('app.name') }}</title>
    <meta name="Keywords" content="{{ $current['tdk']['KEYWORD'] ?? config('app.keywords') }}">
    <meta name="Description" content="{{ $current['tdk']['DESC'] ?? config('app.desc') }}">
@endif
