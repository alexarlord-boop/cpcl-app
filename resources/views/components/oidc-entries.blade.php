{{--  OIDC ENTITIES  --}}
@php
    $oidcEntries = array_filter($sectionEntries, function ($entry) {
        return $entry->getProtocol() == \App\Enums\EntityProtocol::OIDC;
    });
@endphp


@foreach($oidcEntries as $oidc)
    <x-oidc-card :entity="$oidc"/>
@endforeach
