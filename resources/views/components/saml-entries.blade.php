{{--  SAML ENTITIES  --}}

@php
    $samlEntries = array_filter($sectionEntries, function ($entry) {
        return $entry->getProtocol() == \App\Enums\EntityProtocol::SAML;
    });
@endphp

@foreach($samlEntries as $saml)
    <x-saml-card :entity="$saml"/>
@endforeach
