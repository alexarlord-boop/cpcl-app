@if(isset($entity))

    @php
        $id = uniqid();
    @endphp
    <div class="card my-2" id="{{$id}}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">{{$entity->getName()}}</h5>

            <div class="btn-group" role="group" aria-label="Card Actions">
{{--                <x-card-edit-btn :id="$id" :entity="$entity" protocol="saml"/>--}}
                <x-card-process-btn :id="$id" :entity="$entity" protocol="saml"/>
            </div>

        </div>

        <div class="card">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered m-0">
                                <tbody>
                                <tr>
                                    <th scope="row" style="width: 25%;">Name*</th>
                                    <td style="width: 25%;">{{ $entity->getName() }}</td>
                                    <th scope="row" style="width: 25%;">Section</th>
                                    <td style="width: 25%;">{{ $entity->getSection() }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Description*</th>
                                    <td>{{ $entity->getDescription() }}</td>
                                    <th scope="row">Protocol</th>
                                    <td>{{ $entity->getProtocol() }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Resource*</th>
                                    <td><a href="{{ $entity->getResourceLocation() }}">{{ $entity->getResourceLocation() }}</a></td>
                                    <th scope="row">Type*</th>
                                    <td>{{ $entity->getType() }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Entity ID*</th>
                                    <td>{{ $entity->getEntityId() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    @endif
