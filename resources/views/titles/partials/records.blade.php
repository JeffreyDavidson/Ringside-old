@if ($title->hasPastMatches())
    <p>Records</p>
    <p>Longest Title Reign:
        {{ $longestTitleReigns->present()->longestReign }}
        {{-- @foreach ($longestTitleReigns as $reign)
            {{ $reign->wrestler->name }} {{ "(".$reign->length." days)" }}
        @endforeach --}}
    </p>
    <p>Most Title Defenses:
        {{ $mostTitleDefenses->present()->defenses }}
        @foreach ($mostTitleDefenses as $defense)
            {{ $defense->wrestler->name }} {{ "(".$defense->count.")" }}
        @endforeach
    </p>
    <p>Most Title Reigns:
        {{ $mostTitleReigns->present()->reign }}
        @foreach ($mostTitleReigns as $reign)
            {{ $reign->wrestler->name }} {{ "(".$reign->count.")" }}
        @endforeach
    </p>
@endif
