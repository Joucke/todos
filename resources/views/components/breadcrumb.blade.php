@props(['items' => []])

<div class="flex items-center space-x-2 text-xl">
    @foreach($items as $index => $item)
        @if($index > 0)
            <span class="text-gray-400 dark:text-gray-500">></span>
        @endif

        @if(isset($item['url']) && !$loop->last)
            <a href="{{ $item['url'] }}" class="font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors flex items-center space-x-2" @if(isset($item['icon'])) title="{{ $item['title'] }}" @endif>
                @if(isset($item['icon']))
                    <x-icon type="{{ $item['icon'] }}" size="md" />
                @endif
                @if(isset($item['title']) && !isset($item['icon']))
                    {{ $item['title'] }}
                @endif
            </a>
        @else
            <span class="font-semibold text-gray-800 dark:text-gray-200 flex items-center space-x-2" @if(isset($item['icon'])) title="{{ $item['title'] }}" @endif>
                @if(isset($item['icon']))
                    <x-icon type="{{ $item['icon'] }}" size="md" />
                @endif
                @if(isset($item['title']) && !isset($item['icon']))
                    {{ $item['title'] }}
                @endif
            </span>
        @endif
    @endforeach
</div>
