@extends ('layouts.app')

@section('title')
    Choose Monzo Account
@endsection

@section('content')
    <div class="row">
        <h1>Choose account to sync..</h1>
        <ul>
            @forelse ($accounts as $account)
                <li><a href="/choose-account/{{ $account['id'] }}">{{ $account['description'] }}</a></li>
            @empty
                No accounts to choose from.  Something odd has happened, please go home.
            @endforelse
        </ul>
    </div>
@endsection
