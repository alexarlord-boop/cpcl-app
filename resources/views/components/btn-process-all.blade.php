@if(isset($entities))
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <form action="{{ route("process.$protocol") }}" method="post" id="saml-process-form">
                @csrf
                <input type="hidden" name={{$protocol . 'Entity'}} value="{{json_encode(serialize($entities))}}"
                       id="saml-process-input">
                <button type="submit" class="btn btn-lg btn-primary ml-2" id="saml-process-btn">
                    <i class="bi bi-arrow-right"></i> Process Section
                </button>
            </form>
        </div>
@endif
