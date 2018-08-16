@extends ('layouts.app')

@section('title')
    Choose YNAB Budget
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1>Choose Budget to sync to..</h1>

            <ul class="list-group">
                @forelse ($budgets as $budget)
                    <li class="list-group-item"><a href="/ynab/choose-budget/{{ $budget['id'] }}">{{ $budget['name'] }}</a></li>
                @empty
                    No accounts to choose from.  Something odd has happened, please go <a href="/ynab/reset">home</a>.
                @endforelse
            </ul>
        </div>
    </div>
@endsection
