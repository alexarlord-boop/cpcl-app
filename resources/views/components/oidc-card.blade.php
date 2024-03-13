@if(isset($entity))
    @php
        $id = uniqid();
    @endphp

    <div class="card my-2" id="{{$id}}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>{{$entity->getName()}}</h5>

            <div class="btn-group" role="group" aria-label="Card Actions">
{{--                <x-card-edit-btn :id="$id" :entity="$entity"/>--}}
                <x-card-process-btn :id="$id" :entity="$entity" protocol="oidc"/>
            </div>

        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Section:</strong> {{ $entity->getSection() }}<br>
                        <strong>Protocol:</strong> {{ $entity->getProtocol() }}<br>
                        <strong>Type:</strong> {{ $entity->getType() }}<br>
                        <strong>Name:</strong> {{ $entity->getName() }}<br>
                        <strong>Description:</strong> {{ $entity->getDescription() }}<br>
                    </div>
                    <div class="col-md-6">
                        <strong>Resource Location:</strong> <a href="{{ $entity->getResourceLocation() }}">{{ $entity->getResourceLocation() }}</a><br>
                        <strong>Entity ID:</strong> {{ $entity->getEntityId() }}<br>
                        <strong>Dynamic Registration:</strong> {{ $entity->getDynamicRegistration() ? 'Yes' : 'No' }}<br>
                        @if($entity->getClientSecret())
                            <strong>Client Secret:</strong> {{ $entity->getClientSecret() }}<br>
                        @endif
                    </div>
                </div>
            </div>
        </div>


{{--        <div class="card">--}}
{{--            <div class="card-body p-0">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <table class="table table-bordered m-0">--}}
{{--                            <tbody>--}}
{{--                            <tr>--}}
{{--                                <th scope="row" style="width: 25%;">Name</th>--}}
{{--                                <td style="width: 25%;">{{ $entity->getName() }}</td>--}}
{{--                                <th scope="row" style="width: 25%;">Section</th>--}}
{{--                                <td style="width: 25%;">{{ $entity->getSection() }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th scope="row">Description</th>--}}
{{--                                <td>{{ $entity->getDescription() }}</td>--}}
{{--                                <th scope="row">Protocol</th>--}}
{{--                                <td>{{ $entity->getProtocol() }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th scope="row">Resource</th>--}}
{{--                                <td><a href="{{ $entity->getResourceLocation() }}">{{ $entity->getResourceLocation() }}</a></td>--}}
{{--                                <th scope="row">Type</th>--}}
{{--                                <td>{{ $entity->getType() }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th scope="row">Entity ID</th>--}}
{{--                                <td>{{ $entity->getEntityId() }}</td>--}}
{{--                                <th scope="row">Dynamic Registration</th>--}}
{{--                                <td>{{ $entity->getDynamicRegistration() ? 'Yes' : 'No' }}</td>--}}
{{--                            </tr>--}}
{{--                            @if($entity->getClientSecret())--}}
{{--                                <tr>--}}
{{--                                    <th scope="row">Client Secret</th>--}}
{{--                                    <td colspan="3">{{ $entity->getClientSecret() }}</td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endif
