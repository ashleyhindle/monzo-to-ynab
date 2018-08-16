@extends ('layouts.app')

@section('title')
    Welcome!
@endsection

@section('content')
    <div class="row">
        @include('monzo.panel')
        @include('ynab.panel')
    </div>
        @if (session('monzo.chosen_account.id') && session('monzo.expires') > time() && session('ynab.chosen_budget.id') && session('ynab.expires') > time() && session('ynab.chosen_account.id'))
            <div class="row">
                <div class="col">
                    <h1>Sync settings</h1>

                    <ul class="list-group">
                        <li class="list-group-item"><strong>Monzo account:</strong> {{ session('monzo.chosen_account.name') }}</li>
                        <li class="list-group-item"><strong>YNAB budget:</strong> {{ session('ynab.chosen_budget.name') }}</li>
                        <li class="list-group-item"><strong>YNAB account:</strong> {{ session('ynab.chosen_account.name') }}</li>
                    </ul>

                    <a href="/monzo/setup-webook" class="mt-2 btn btn-lg btn-success">Start syncing</a>
                </div>
            </div>
        @endif
@endsection
