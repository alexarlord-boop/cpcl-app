@php
    $statusMapping = array(
        'success' => 'success',
        'error' => 'danger'
    );

    $iconMapping = array(
        'success' => 'check-circle',
        'error' => 'exclamation-triangle'
    )
@endphp
@foreach($statusMapping as $status => $tag)
    @if(session($status) !== null)
        <div class="position-fixed p-3" style="z-index: 10000; top: 0; right: 0;">
            <div class="toast" data-delay="10000" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header d-flex justify-content-between">
                    <i class="bi bi-{{$iconMapping[$status]}} text-{{$status}}"></i>
                    <small>now</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    {{ session($status) }}
                </div>
            </div>
        </div>
    @endif
@endforeach

