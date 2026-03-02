@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Users</h4>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">S.No</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Phone</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $user->name }}</td>
                            <td class="text-center align-middle">{{ $user->email }}</td>
                            <td class="text-center align-middle">{{ $user->phone ?? 'N/A' }}</td>
                            <td class="align-middle">
                                @php
                                    $addressData = $user->address;
                                    if(is_string($addressData) && is_array(json_decode($addressData, true)) && (json_last_error() == JSON_ERROR_NONE)){
                                        $addressData = json_decode($addressData, true);
                                    }
                                @endphp

                                @if(is_array($addressData))
                                    @if(isset($addressData['name'])) <strong>{{ $addressData['name'] }}</strong><br> @endif
                                    @if(isset($addressData['address'])) {{ $addressData['address'] }}<br> @endif
                                    @if(isset($addressData['city']) || isset($addressData['state']) || isset($addressData['pincode']))
                                        {{ implode(', ', array_filter([$addressData['city'] ?? null, $addressData['state'] ?? null, $addressData['pincode'] ?? null])) }}<br>
                                    @endif
                                    @if(isset($addressData['phone'])) <small class="text-muted">Phone: {{ $addressData['phone'] }}</small> @endif
                                @else
                                    {{ $user->address ?? 'N/A' }}
                                @endif
                                
                                @if(!is_array($addressData) && ($user->city || $user->state || $user->pincode))
                                    <br>
                                    <small class="text-muted">
                                        {{ implode(', ', array_filter([$user->city, $user->state, $user->pincode])) }}
                                    </small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
