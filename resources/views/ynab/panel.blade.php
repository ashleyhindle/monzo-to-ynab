<div class="col-sm">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">YNAB</h5>
            <p class="card-text">
                After you've chosen a Monzo account to sync from we'll link up with YNAB to see which account transactions will be synced to.

                We'd recommend setting up the account within YNAB ahead of time
            </p>

            {{-- We have linked with Monzo and have chosen an account --}}
            @if (session('monzo.expires') > time() && session('monzo.chosen_account.id') !== null)
                <a href="/ynab/auth" class="btn btn-dark">Link YNAB Account</a>
            @else
                <button disabled class="btn btn-dark" title="Cannot link until we have chosen a Monzo account">Link YNAB Account</button>
            @endif
        </div>
    </div>
</div>
