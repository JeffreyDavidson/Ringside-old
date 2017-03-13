<h1>{{ $wrestler->name }}</h1>
<p>{{ $wrestler->hometown }}</p>
<p>{{ $wrestler->formatted_height }}</p>
<p>{{ $wrestler->weight }} lbs.</p>
<p>{{ $wrestler->signature_move }}</p>

<p>Current Managers:</p>
@foreach($wrestler->currentManagers as $manager)
    {{ $manager->name }}
@endforeach

<p>Previous Managers</p>
@foreach($wrestler->previousManagers as $manager)
    {{ $manager->name }}
@endforeach

<p>Titles Held</p>
@foreach($wrestler->titles as $title)
    {{ $title->name }}
@endforeach
