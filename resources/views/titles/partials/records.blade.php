<h3>Records</h3>
<p>Longest Title Reign:
    @if ($longestTitleReigns->isEmpty())
        No Record Set
    @else
        {{ $longestTitleReigns->present()->longestReign }}
        @foreach ($longestTitleReigns as $reign)
            {{ $reign->wrestler->name }} {{ "(".$reign->length." days)" }}
        @endforeach
    @endif
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
