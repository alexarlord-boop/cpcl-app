@extends('layouts.master')
@section('title', 'Proxy objects')

@section('content')
    <div class="container mt-5">
        <ul class="list-group">
            {{-- OIDC Clients --}}
            <h5>OIDC Clients</h5>
            @if ($oidcClients->isNotEmpty())
                <li class="list-group-item">

                    @foreach ($oidcClients as $entry)
                        <x-delete-btn :entry="$entry" protocol="oidc" :type="\App\Enums\EntityType::RP"
                                      :entryId="$entry->id"/>
                        <span>{{ $entry->name }}</span>
                    @endforeach
                </li>
            @else
                <li class="list-group-item">No content</li>
            @endif
            <p class="my-2"></p>

            {{-- IDP Entries --}}
            <h5>IDP Entries</h5>
            @if ($idpEntries->isNotEmpty())
                <li class="list-group-item">
                    @foreach ($idpEntries as $entry)
                        <x-delete-btn :entry="$entry" protocol="saml" :type="\App\Enums\EntityType::IDP"
                                      :entryId="$entry->entity_id"/>
                        <span>{{ $entry->entity_id }}</span>
                    @endforeach
                </li>
            @else
                <li class="list-group-item">No content</li>
            @endif
            <p class="my-2"></p>

            {{-- SP Entries --}}
            <h5>SP Entries</h5>
            @if ($spEntries->isNotEmpty())
                <li class="list-group-item">
                    @foreach ($spEntries as $entry)
                        <x-delete-btn :entry="$entry" protocol="saml" :type="\App\Enums\EntityType::SP"
                                      :entryId="$entry->entity_id"/>
                        <span>{{ $entry->entity_id }}</span>
                    @endforeach
                </li>
            @else
                <li class="list-group-item">No content</li>
            @endif
            <p class="my-2"></p>
        </ul>
    </div>

@endsection

@section('styles')
    <!-- Additional styles specific to this page -->
@endsection

@section('scripts')
    <!-- Additional scripts specific to this page -->
@endsection
