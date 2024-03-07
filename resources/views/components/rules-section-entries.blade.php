@if(isset($sectionEntries) && count($sectionEntries) > 0)
    <pre>{{ print_r($sectionEntries, true) }}</pre>
@else
    <p>Nothing to show</p>
@endif
