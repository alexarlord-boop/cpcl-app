@extends('layouts.master')
@section('title', 'Proxy objects')

@section('content')
    <ul class="list-group">
        {{-- OIDC Clients --}}
        @foreach ($oidcClients as $client)
            <li class="list-group-item">
                <span>ID: {{ $client->id }}</span> |
                <span>Name: {{ $client->name }}</span>
            </li>
        @endforeach

        {{-- IDP Entries --}}
        @foreach ($idpEntries as $entry)
            <li class="list-group-item">
                <span>ID: {{ $entry->id }}</span> |
                <span>Name: {{ $entry->name }}</span>
            </li>
        @endforeach

        {{-- SP Entries --}}
        @foreach ($spEntries as $entry)
            <li class="list-group-item">
                <span>ID: {{ $entry->id }}</span> |
                <span>Name: {{ $entry->name }}</span>
            </li>
        @endforeach
    </ul>

@endsection

@section('styles')
    <!-- Additional styles specific to this page -->
@endsection

@section('scripts')
    <!-- Additional scripts specific to this page -->
@endsection
