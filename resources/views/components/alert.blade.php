@php
    $statusMapping = array(
        'success' => 'success',
        'error' => 'danger',
        'info' => 'warning'
    );

    $iconMapping = array(
        'success' => 'check-circle',
        'error' => 'exclamation-triangle',
         'info' => 'exclamation-triangle'
    )
@endphp
<div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 200px;"
     class="d-flex flex-column align-items-end">
    <div class="position-fixed p-3" style="z-index: 10000; top: 0; right: 0;">
        @foreach($statusMapping as $status => $tag)
            @if(session($status) !== null)

                <div class="toast" data-delay="5000" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header d-flex justify-content-between">
                        <i class="bi bi-{{$iconMapping[$status]}} text-{{$tag}}"></i>
                        <small>{{$status}}</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body pb-0">
                        <pre class="pb-3">{{ session($status) }}</pre>
                    </div>
                </div>

            @endif
        @endforeach
    </div>
</div>

