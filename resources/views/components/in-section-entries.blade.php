@if(isset($sectionEntries) && count($sectionEntries) > 0)
    <x-saml-entries :sectionEntries="$sectionEntries"/>
@else
    <p>Nothing to show</p>
@endif
