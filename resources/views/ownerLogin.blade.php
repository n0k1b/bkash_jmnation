@extends('layout.app')

@section('main-panel')
<div class="page-header">
    <h3 class="page-title">
        Apartments
    </h3>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive" id="owner-login">

        </div>
    </div>
</div>

@endsection

@section('page-js')
<script src="{{ asset('assets/admin/owner-login.js') }}"></script>
@endsection
