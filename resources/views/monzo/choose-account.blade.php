@extends ('layouts.app')

@section('title')
    Choose Monzo Account
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>Choose account to sync..</h1>

            <ul class="list-group">
                @forelse ($accounts as $account)
                    <li class="list-group-item"><a href="/monzo/choose-account/{{ $account['id'] }}">{{ $account['description'] }}</a></li>
                @empty
                    No accounts to choose from.  Something odd has happened, please go <a href="/monzo/reset">home</a>.
                @endforelse
            </ul>
        </div>
    </div>
@endsection
