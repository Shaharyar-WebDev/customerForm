<div class="bg-white shadow-lg rounded-xl overflow-hidden p-5 flex flex-col items-center text-center max-w-xs mx-auto">
    {{-- Thumbnail --}}
    @if($getRecord()->thumbnail)
        <img src="{{ asset('storage/' . $getRecord()->thumbnail) }}"
             alt="{{ $getRecord()->title }}"
             class="w-36 h-36 object-cover rounded-lg mb-4">
    @endif

    {{-- Title --}}
    <h2 class="text-xl font-semibold text-gray-800 mb-1">{{ $getRecord()->title }}</h2>

    {{-- Slug --}}
    <p class="text-sm text-gray-500 mb-3 truncate">{{ $getRecord()->slug }}</p>

    {{-- Collapsible Audio Player --}}
    @if($getRecord()->audio)
        <details class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 cursor-pointer">
            <summary class="font-medium text-blue-600 select-none">▶ Play Audio</summary>
            <audio controls class="w-full mt-2 rounded">
                <source src="{{ asset('storage/' . $getRecord()->audio) }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </details>
    @endif
</div>
