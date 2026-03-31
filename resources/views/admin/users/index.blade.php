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
                                    $latestOrder = $user->orders()->latest()->first();
                                    $displayAddress = null;
                                    $isLegacy = false;
                                    $sourceLabel = '';

                                    if ($latestOrder && !empty($latestOrder->shipping_address)) {
                                        $displayAddress = $latestOrder->shipping_address;
                                        $sourceLabel = 'Last Ordered Address';
                                    } else {
                                        $defaultAddress = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();
                                        if ($defaultAddress) {
                                            $displayAddress = [
                                                'name' => $defaultAddress->first_name . ' ' . $defaultAddress->last_name,
                                                'door_number' => $defaultAddress->door_number,
                                                'street' => $defaultAddress->street,
                                                'city' => $defaultAddress->city,
                                                'state' => $defaultAddress->state,
                                                'pincode' => $defaultAddress->post_code,
                                                'phone' => $defaultAddress->phone_number
                                            ];
                                            $sourceLabel = 'Default Address';
                                        } else {
                                            $legacyAddress = $user->address;
                                            if (is_string($legacyAddress) && is_array(json_decode($legacyAddress, true))) {
                                                $displayAddress = json_decode($legacyAddress, true);
                                            } elseif (is_array($legacyAddress)) {
                                                $displayAddress = $legacyAddress;
                                            }
                                            $isLegacy = true;
                                            $sourceLabel = 'Legacy Address';
                                        }
                                    }
                                @endphp

                                @if($displayAddress)
                                    <div class="p-2 border rounded bg-light" style="font-size: 0.9rem;">
                                        <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;">{{ $sourceLabel }}</small>
                                        
                                        <strong>{{ $displayAddress['name'] ?? ($user->name ?? 'User') }}</strong><br>
                                        
                                        @if(!empty($displayAddress['door_number'])){{ $displayAddress['door_number'] }}, @endif
                                        @if(!empty($displayAddress['door_no'])){{ $displayAddress['door_no'] }}, @endif
                                        @if(!empty($displayAddress['street'])){{ $displayAddress['street'] }}, @endif
                                        {{ $displayAddress['address'] ?? '' }}
                                        
                                        @if(isset($displayAddress['city']) || isset($displayAddress['state']) || isset($displayAddress['pincode']))
                                            <br>{{ implode(', ', array_filter([$displayAddress['city'] ?? null, $displayAddress['state'] ?? null, $displayAddress['pincode'] ?? null])) }}
                                        @endif
                                        
                                        @if(!empty($displayAddress['phone']))
                                            <div class="mt-1"><small class="text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $displayAddress['phone'] }}</small></div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">No address available</span>
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
