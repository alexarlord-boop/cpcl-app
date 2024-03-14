<div class="config-sections">
    <div class="d-flex justify-content-between align-items-center">
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
        <ul class="nav nav-bar">
            <li class="btn-group dropleft">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Actions
                </a>
                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{route('clear.cache')}}"><i class="bi bi-trash text-danger"></i>
                        Close</a>
                    <a class="dropdown-item" href="#">Download configuration</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
    </div>
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

