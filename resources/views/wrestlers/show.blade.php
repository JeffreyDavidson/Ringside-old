<h1>{{ $wrestler->name }}</h1>
<p>{{ $wrestler->hometown }}</p>
<p>{{ $wrestler->formatted_height }}</p>
<p>{{ $wrestler->weight }} lbs.</p>
<p>{{ $wrestler->signature_move }}</p>

@if($wrestler->currentManagers->count() > 0)
    <p>Current Managers:</p>
    @foreach($wrestler->currentManagers as $manager)
        {{ $manager->name }}
    @endforeach
@endif

@if($wrestler->currentManagers->count() > 0)
    <p>Previous Managers</p>
    @foreach($wrestler->previousManagers as $manager)
        {{ $manager->name }}
    @endforeach
@endif

@if($wrestler->titles->count() > 0)
    <p>Titles Held</p>
    @foreach($wrestler->titles->groupByTitle() as $title)
        {{ $title[0]->title->name }} {{ '(' . $title->count(). 'x)'}}
    @endforeach
@endif

@if($wrestler->matches->count() > 0)
    <p>Matches</p>
    <table>
        <thead>
            <th>Event</th>
        </thead>
        <tbody>
            @foreach($wrestler->matches as $match)
                <tr>{{ $match->event->name }}</tr>
            @endforeach
        </tbody>
    </table>
@endif
