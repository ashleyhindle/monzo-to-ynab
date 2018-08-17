<div class="card">
    <div class="card-body">
        <h5 class="card-title">Monzo</h5>
        <p class="card-text">
            Let's start by linking up with your Monzo account, to see where we'll be syncing transactions from
        </p>
    </div>
    <div class="card-footer">
    @if (session('monzo.expires') > time())
        <button class="btn btn-success disabled" title="Already successfully authenticated with Monzo" data-toggle="tooltip" data-placement="top">Linked</button>
        @if (session('monzo.chosen_account.id') === null)
            <a href="/monzo/choose-account" class="btn btn-primary" style="background-color: #fc4f5a; border-color: #f3666d;">Choose Monzo Account</a>
        @else
            <button class="btn btn-success disabled" title="{{ session('monzo.chosen_account.name') }}" data-toggle="tooltip" data-placement="top">Account chosen</button>
        @endif

        <ul class="list-inline">
            <li class="list-inline-item"><a href="{{ url('/monzo/reset') }}">Start over</a></li>
            @if (session('monzo.chosen_account.id') !== null)
            <li class="list-inline-item"><a href="/monzo/reset-account">Choose new account</a></li>
            @endif
        </ul>
        @if (session('monzo.chosen_account.id') && session('monzo.expires') > time())
            <hr />
            <a href="/monzo/cancel/{{ session('monzo.chosen_account.id') }}" data-toggle="tooltip" data-placement="top" title="If you don't want to sync Monzo to YNAB anymore, or you want to start over" class="btn btn-sm btn-danger">Delete old syncing setups for this account</a>
        @endif
    @else
        <a href="/monzo/auth" class="btn btn-primary" style="background-color: #fc4f5a; border-color: #f3666d;">Link Monzo Account</a>
    @endif
    </div>
</div>
