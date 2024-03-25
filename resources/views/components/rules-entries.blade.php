@if(isset($sectionEntries) && count($sectionEntries) > 0)

    {{--    <x-saml-entries :sectionEntries="$sectionEntries"/>--}}
    {{--    <x-oidc-entries :sectionEntries="$sectionEntries"/>--}}

    @foreach($sectionEntries as $e)
        <pre>{{print_r($e, true)}}</pre>
    @endforeach

@else
    <p>Nothing to show</p>
@endif
