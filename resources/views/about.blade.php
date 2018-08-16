@extends ('layouts.app')

@section('title')
    Welcome!
@endsection

@section('content')
    <div class="row">
        @include('monzo.panel')
        @include('ynab.panel')
    </div>
@endsection
