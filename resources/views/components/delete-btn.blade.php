<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $id }}">
    <i class="bi bi-trash"></i>
</button>
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal{{ $id }}" tabindex="-1" role="dialog"
     aria-labelledby="deleteModalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $id }}">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete "{{ $id }}"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form action="{{ route('deleteEntry', ['type' => $type, 'id' => $id]) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

