<div class="config-sections">
    <div class="d-flex align-items-center">
        <ul class="nav nav-tabs config-sections mr-5" id="entityTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'saml' ? ' active' : '' }}" id="saml-tab" data-toggle="tab" href="#saml" role="tab" aria-controls="saml"
                   aria-selected="true">SAML</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'oidc' ? ' active' : '' }}" id="oidc-tab" data-toggle="tab" href="#oidc" role="tab" aria-controls="oidc"
                   aria-selected="false">OIDC</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'rules' ? ' active' : '' }}" id="rules-tab" data-toggle="tab" href="#rules" role="tab" aria-controls="rules"
                   aria-selected="false">Rules</a>
            </li>
        </ul>
    </div>
    <div class="tab-content mt-2">
        <!-- SAML Tab -->
        <div class="tab-pane {{ $activeTab === 'saml' ? ' show active' : '' }}" id="saml" role="tabpanel" aria-labelledby="saml-tab">
            <x-saml-entries :sectionEntries="$samlSection"/>
        </div>

        <!-- OIDC Tab -->
        <div class="tab-pane {{ $activeTab === 'oidc' ? ' show active' : '' }}" id="oidc" role="tabpanel" aria-labelledby="oidc-tab">
            <x-oidc-entries :sectionEntries="$oidcSection"/>
        </div>


        <!-- Rules Tab -->
        <div class="tab-pane {{ $activeTab === 'rules' ? ' show active' : '' }}" id="rules" role="tabpanel" aria-labelledby="rules-tab">
            <x-rules-entries :sectionEntries="$rulesSection"/>
        </div>
    </div>
</div>

