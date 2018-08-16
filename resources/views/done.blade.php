@extends ('layouts.app')

@section('title')
    Done!
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>What a rollercoaster!</h1>
            <h4>Well, that's it. All done, dusted and sorted.</h4>
            <p>
                Now go buy something to test it out, and if you have any issues <a href="mailto:monzotoynab@ashleyhindle.com">email me</a> and I'll probably help
            </p>
            <h1>Now what?</h1>

            <a href="/" class="btn btn-lg btn-outline-dark">Setup <i>another</i> Monzo sync</a>
            <a href="https://monzo.me/ashleyhindle/?d=You%20rock" class="btn btn-lg btn-outline-success">Test syncing by donating to my next curry</a>
            <a href="https://twitter.com/intent/tweet?url=https%3A%2F%2Fmonzo-to-ynab.ashleyhindle.com&via=%40ashleyhindle&text=Pretty%20easy%20and%20awesome%20setup%20to%20sync%20@monzo%20with%20@ynab" class="btn btn-lg btn-outline-primary">Tweet that this was awesome</a>
        </div>
    </div>
@endsection
