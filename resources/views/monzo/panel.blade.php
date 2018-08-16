<div class="col-sm">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Monzo</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

            @if (session('monzo.expires') > time())
                <button disabled class="btn btn-success">Linked!</button><br />
                @if (session('monzo.chosen_account.id') === null)
                    <a href="/monzo/choose-account" class="btn btn-primary" style="background-color: #fc4f5a; border-color: #f3666d;">Choose Monzo Account</a>
                @else
                    <button disabled class="btn btn-success">Account chosen</button><br />
                @endif
                <a href="{{ url('/monzo/reset') }}">Reset</a>
            @else
                <a href="/monzo/auth" class="btn btn-primary" style="background-color: #fc4f5a; border-color: #f3666d;">Link Monzo Account</a>
            @endif
        </div>
    </div>
</div>
