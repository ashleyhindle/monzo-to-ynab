@extends ('layouts.app')

@section('title')
    Choose YNAB Account
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>Choose YNAB account to sync to..</h1>
            <p>Syncing from Monzo account '{{ session('monzo.chosen_account.name') }}'</p>

            <ul class="list-group">
                @forelse ($accounts as $account)
                    <li class="list-group-item"><a href="/ynab/choose-account/{{ $account['id'] }}">{{ $account['name'] }}</a></li>
                @empty
                    No accounts to choose from.  Something odd has happened, please go <a href="/ynab/reset">home</a>.
                @endforelse
            </ul>
        </div>
    </div>
@endsection
