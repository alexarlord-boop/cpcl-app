<div class="card my-2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>{{$entity->getName()}}</h5>

        <div class="btn-group" role="group" aria-label="Card Actions">
            <x-card-edit-btn :entity="$entity"/>
            <x-card-process-btn/>
        </div>

    </div>
    <div class="card-body">
            <p>
                @php
                    print_r($entity);
                @endphp
            </p>
    </div>
</div>
