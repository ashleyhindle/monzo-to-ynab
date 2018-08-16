@extends ('layouts.app')

@section('title')
    Privacy!
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <p>
                Monzo to YNAB does not store any personally identifiable information its servers from your Monzo or YNAB account.

                All Monzo and YNAB tokens processed are immediately deleted after use, and the only token we need to keep for a long period is encrypted with the AES-128-CBC cipher in a secured MySQL database.

                No data processed is currently passed to any third-party, and will never be.
            </p>
            <hr />
            <p>
                For any further information please <a href="mailto:monzotoynab@ashleyhindle.com">email me</a>
            </p>
        </div>
    </div>
@endsection
