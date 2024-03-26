{{--  OIDC ENTITIES  --}}
@if(isset($sectionEntries) && count($sectionEntries) > 0)

    {{--    <x-saml-entries :sectionEntries="$sectionEntries"/>--}}
    {{--    <x-oidc-entries :sectionEntries="$sectionEntries"/>--}}

    @foreach($sectionEntries as $e)
        <x-oidc-card :entity="$e"/>
    @endforeach

    <x-btn-process-all :entities="$sectionEntries" protocol="oidc"/>

@else
    <p>Nothing to show</p>
@endif
