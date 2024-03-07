<div class="card my-1">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>{{$entity->getName()}}</h5>

        <div class="btn-group" role="group" aria-label="Card Actions">
            <x-card-edit-btn :entity="$entity"/>
            <x-card-process-btn/>
        </div>

    </div>
    <div class="card-body">
        <div class="card-body">
           <pre>
                @php
                    print_r($entity);
                @endphp
            </pre>
        </div>
    </div>
</div>
