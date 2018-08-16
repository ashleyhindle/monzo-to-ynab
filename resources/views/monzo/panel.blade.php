<div class="col-sm">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Monzo</h5>
            <p class="card-text">
                Let's start by linking up with your Monzo account, to see where we'll be syncing transactions from
            </p>

            @if (session('monzo.expires') > time())
                <button disabled class="btn btn-success" title="Already successfully authenticated with Monzo">Linked</button>
                @if (session('monzo.chosen_account.id') === null)
                    <a href="/monzo/choose-account" class="btn btn-primary" style="background-color: #fc4f5a; border-color: #f3666d;">Choose Monzo Account</a>
                @else
                    <button disabled class="btn btn-success" title="{{ session('monzo.chosen_account.name') }}">Account chosen</button>

                @endif

                <ul class="list-inline">
                    <li class="list-inline-item"><a href="{{ url('/monzo/reset') }}">Start over</a></li>
                    @if (session('monzo.chosen_account.id') !== null)
                    <li class="list-inline-item"><a href="/monzo/reset-account">Choose new account</a></li>
                    @endif
                </ul>
                @if (session('monzo.chosen_account.id') && session('monzo.expires') > time())
                    <hr />
                    <a href="/monzo/cancel/{{ session('monzo.chosen_account.id') }}" class="btn btn-sm btn-danger">Delete old syncing setups</a>
                @endif
            @else
                <a href="/monzo/auth" class="btn btn-primary" style="background-color: #fc4f5a; border-color: #f3666d;">Link Monzo Account</a>
            @endif
        </div>
    </div>
</div>
