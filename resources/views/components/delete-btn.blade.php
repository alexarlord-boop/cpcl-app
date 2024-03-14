<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $client->id }}">
    <i class="bi bi-trash"></i>
</button>
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $client->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $client->id }}">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete "{{ $client->name }}"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form action="{{ route('deleteClient', ['id' => $client->id]) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
