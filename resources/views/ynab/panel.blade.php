    <div class="card">
        <div class="card-body">
            <h5 class="card-title">YNAB</h5>
            <p class="card-text">
                After you've chosen a Monzo account to sync from we'll link up with YNAB to see which account transactions will be synced to.

                We'd recommend setting up the account within YNAB ahead of time
            </p>
        </div>
        <div class="card-footer">
        {{-- We have a YNAB access token that isn't expired --}}
        @if (session('ynab.expires') > time())
            <button disabled class="btn btn-success" title="Already successfully authenticated with YNAB" data-toggle="tooltip" data-placement="top">Linked</button>
            @if (session('ynab.chosen_budget.id') === null)
                <a href="/ynab/choose-budget" class="btn btn-dark">Choose YNAB Budget</a>
            @else
                <button disabled class="btn btn-success" title="{{ session('ynab.chosen_budget.name') }}"> data-toggle="tooltip" data-placement="top"Budget chosen</button>
                @if (session('ynab.chosen_account.id') === null)
                    <a href="/ynab/choose-account" class="btn btn-dark">Choose YNAB Account</a>
                @else
                    <button disabled class="btn btn-success" title="{{ session('ynab.chosen_account.name') }}" data-toggle="tooltip" data-placement="top">Account chosen</button>
                @endif
            @endif

            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ url('/ynab/reset') }}">Start over</a></li>
                @if (session('ynab.chosen_budget.id') !== null)
                    <li class="list-inline-item"><a href="/ynab/reset-budget">Choose new budget</a></li>
                @endif

                @if (session('ynab.chosen_account.id') !== null)
                    <li class="list-inline-item"><a href="/ynab/reset-account">Choose new account</a></li>
                @endif
            </ul>

        @else
            {{-- We have linked with Monzo and have chosen an account --}}
            @if (session('monzo.expires') > time() && session('monzo.chosen_account.id') !== null)
                <a href="/ynab/auth" class="btn btn-dark">Link YNAB Account</a>
            @else
                <button class="btn" style="background-color: #343a40; border-color: #343a40; color: #FFFFFF; cursor:not-allowed;" title="Cannot link until you have linked a Monzo account" data-toggle="tooltip" data-placement="top">Link YNAB Account</button>
            @endif
        @endif
        </div>
    </div>
