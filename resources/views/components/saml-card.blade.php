@if(isset($entity))

    @php
        $id = uniqid();

        $th_style = "";

    @endphp
    <div class="card my-3" id="{{$id}}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="m-0">{{$entity->getName()}}
            <span class="p">
                {{--            <span class="badge badge-secondary m-0">{{ $entity->getProtocol() }}</span>--}}
{{--                <span class="badge badge-secondary m-0">{{ $entity->getSection() }}</span>--}}
                <span class="badge badge-secondary m-0">{{ $entity->getSection() }}</span></span>
            </h4>



            <div class="btn-group" role="group" aria-label="Card Actions">
{{--                <x-card-edit-btn :id="$id" :entity="$entity" protocol="saml"/>--}}
{{--                <x-card-process-btn :id="$id" :entity="$entity" protocol="saml"/>--}}
            </div>
        </div>
        <div class="">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table m-0">
                                <tbody>
                                <tr>
{{--                                    <th scope="row" style="width: 15%;">*Name:</th>--}}
{{--                                    <td class="border-right" style="width: 35%;">{{ $entity->getName() }}</td>--}}
{{--                                    <th scope="row" style="width: 25%;">Section:</th>--}}
{{--                                    <td style="width: 25%;">{{ $entity->getSection() }}</td>--}}
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 15%;">Description:</th>
                                    <td class="border-left">{{ $entity->getDescription() }}</td>
{{--                                    <th scope="row">Protocol:</th>--}}
{{--                                    <td>{{ $entity->getProtocol() }}</td>--}}
                                </tr>
                                <tr>
                                    <th scope="row">Metadata:</th>
                                    <td class="border-left"><a href="{{ $entity->getResourceLocation() }}">{{ $entity->getResourceLocation() }}</a></td>
{{--                                    <th scope="row">*Type:</th>--}}
{{--                                    <td >{{ $entity->getType() }}</td>--}}
                                </tr>
                                <tr>
                                    <th scope="row">Entity ID:</th>
                                    <td class="border-left">{{ $entity->getEntityId() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    @endif
