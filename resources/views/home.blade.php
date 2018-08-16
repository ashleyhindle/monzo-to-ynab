@extends ('layouts.app')

@section('title')
    Welcome!
@endsection

@section('content')
    <div class="row">
        @include('monzo.panel')
        @include('ynab.panel')

        @if (session('monzo.chosen_account.id') && session('monzo.expires') > time() && session('ynab.chosen_budget.id') && session('ynab.expires') > time() && session('ynab.chosen_account.id'))
            <div class="row">
                <div class="col">
                    You are linked with Monzo and YNAB.  Your access tokens haven't expired.  You have chosen a Monzo account, YNAB budget and YNAB account.  We are ready to store this in the database, and setup the Monzo webhook

                    <ul class="list-group">
                        <li class="list-group-item"><strong>Monzo account:</strong> {{ session('monzo.chosen_account.name') }}</li>
                        <li class="list-group-item"><strong>YNAB budget:</strong> {{ session('ynab.chosen_budget.name') }}</li>
                        <li class="list-group-item"><strong>YNAB account:</strong> {{ session('ynab.chosen_account.name') }}</li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endsection
