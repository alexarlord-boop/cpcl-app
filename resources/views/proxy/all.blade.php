@extends('layouts.master')
@section('title', 'Proxy objects')

@section('content')
    <div class="container">
        <ul class="list-group">
            {{-- OIDC Clients --}}
            @foreach ($oidcClients as $client)
                <li class="list-group-item">
                    <x-delete-btn/>
                    <span>{{ $client->name }}</span>
                </li>
            @endforeach

            {{-- IDP Entries --}}
            @foreach ($idpEntries as $entry)
                <li class="list-group-item">
                    <x-delete-btn/>
                    <span>{{ $entry->entity_id }}</span>
                    {{--                <span>Name: {{ $entry->name }}</span>--}}
                </li>
            @endforeach

            {{-- SP Entries --}}
            @foreach ($spEntries as $entry)
                <li class="list-group-item">
                    <x-delete-btn/>
                    <span>{{ $entry->entity_id }}</span>
                    {{--                <span>Name: {{ $entry->name }}</span>--}}
                </li>
            @endforeach
        </ul>
    </div>

@endsection

@section('styles')
    <!-- Additional styles specific to this page -->
@endsection

@section('scripts')
    <!-- Additional scripts specific to this page -->
@endsection
