@if ($title->hasPastMatches())
    <p>Records</p>
    <p>Longest Title Reign:
        @foreach ($title->longest_title_reign() as $reign)
            {{ $reign->wrestler->name }} {{ "(".$reign->length." days)" }}
        @endforeach
    </p>
    <p>Most Title Defenses:
        @foreach ($title->most_title_defenses() as $defense)
            {{ $defense->wrestler->name }} {{ "(".$defense->count.")" }}
        @endforeach
    </p>
    <p>Most Title Reigns:
        @foreach ($title->most_title_reigns() as $reign)--}}
            $reign->wrestler->name }} {{ "(".$reign->count.")" }}
        @endforeach
    </p>
@endif
