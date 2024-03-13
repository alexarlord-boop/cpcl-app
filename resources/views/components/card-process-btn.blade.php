@if(isset($entity))

    <form action="{{ route("process.$protocol") }}" method="post" id="{{$id}}-form">
        @csrf
        <input type="hidden" name={{$protocol . 'Entity'}} value="{{json_encode(serialize($entity))}}" id="{{$id}}-process-input">
        <button type="submit" class="btn btn-sm btn-success ml-2" id="{{$id}}-process-btn">
            <i class="bi bi-arrow-right"></i> Process
        </button>
    </form>
@endif
