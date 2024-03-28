@if(isset($entity))
    @php
        $id = uniqid();
    @endphp

    <div class="card my-2" id="{{$id}}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="m-0">{{$entity->getName()}}
                <span class="p">
                {{--            <span class="badge badge-secondary m-0">{{ $entity->getProtocol() }}</span>--}}
                    {{--                <span class="badge badge-secondary m-0">{{ $entity->getSection() }}</span>--}}
                <span class="badge badge-secondary m-0">{{ $entity->getSection() }}</span></span>
            </h4>

            <div class="btn-group" role="group" aria-label="Card Actions">
                {{--                <x-card-edit-btn :id="$id" :entity="$entity"/>--}}
{{--                <x-card-process-btn :id="$id" :entity="$entity" protocol="oidc"/>--}}
            </div>

        </div>


        <div class="">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table m-0">
                            <tbody>
                            <tr>
                                <th scope="row">Description:</th>
                                <td class="border-left">{{ $entity->getDescription() }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Redirect uri:</th>
                                <td class="border-left">
                                    <a href="{{ $entity->getResourceLocation() }}">{{ $entity->getResourceLocation() }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Entity ID:</th>
                                <td class="border-left">{{ $entity->getEntityId() }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Secret:</th>
                                <td class="border-left">{{ $entity->getClientSecret() }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Scopes:</th>
                                <td class="border-left">{{ implode(', ', json_decode($entity->getScopes())) }}</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
