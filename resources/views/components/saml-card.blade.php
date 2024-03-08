@if(isset($entity))

    @php
        $id = uniqid();
    @endphp
    <div class="card my-2" id="{{$id}}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>{{$entity->getName()}}</h5>

            <div class="btn-group" role="group" aria-label="Card Actions">
                <x-card-edit-btn :id="$id" :entity="$entity"/>
                <x-card-process-btn :id="$id" :entity="$entity"/>
            </div>

        </div>
        <div class="card-body">
            <pre>
                @php
                    print_r($entity);
                @endphp
            </pre>
        </div>
    </div>

@endif
