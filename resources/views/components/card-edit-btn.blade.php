@if(isset($entity))
    @php
        $editModalId = 'editModal_' . uniqid();
    @endphp

    <form action="{{route("edit.$protocol")}}" method="post" id="{{$id}}-form">
        @csrf
        <button type="submit" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="{{'#' . $editModalId}}">
            <i class="bi bi-pencil"></i> Edit
        </button>
    </form>



    <!-- Process Modal -->
    <div class="modal fade" id="{{ $editModalId }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit <span>{{$entity->getName()}}</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your edit form or content here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


@endif
