@if(isset($entities))
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <form action="{{ route("process.$protocol.all") }}" method="post" id="{{$protocol}}-process-form">
                @csrf
                <input type="hidden" name={{$protocol . 'Entities'}} value="{{json_encode(serialize($entities))}}"
                       id="{{$protocol}}-process-input">
                <button type="submit" class="btn btn-lg btn-primary ml-2" id="{{$protocol}}-process-btn">
                    <i class="bi bi-arrow-right"></i> Process Section
                </button>
            </form>
        </div>
@endif
