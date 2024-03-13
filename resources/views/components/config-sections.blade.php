<div class="config-sections">
    <ul class="nav nav-tabs config-sections" id="entityTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="in-tab" data-toggle="tab" href="#in" role="tab" aria-controls="in"
               aria-selected="true">IN</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="out-tab" data-toggle="tab" href="#out" role="tab" aria-controls="out"
               aria-selected="false">OUT</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rules-tab" data-toggle="tab" href="#rules" role="tab" aria-controls="rules"
               aria-selected="false">Rules</a>
        </li>
    </ul>
    <div class="tab-content mt-2">
        <!-- IN Tab -->
        <div class="tab-pane fade show active" id="in" role="tabpanel" aria-labelledby="in-tab">
            <x-in-section-entries :sectionEntries="$inSection"/>
        </div>

        <!-- OUT Tab -->
        <div class="tab-pane fade" id="out" role="tabpanel" aria-labelledby="out-tab">
            <x-out-section-entries :sectionEntries="$outSection"/>
        </div>

        <!-- Rules Tab -->
        <div class="tab-pane fade" id="rules" role="tabpanel" aria-labelledby="rules-tab">
            <x-rules-section-entries :sectionEntries="$rulesSection"/>
        </div>
    </div>
</div>

