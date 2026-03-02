@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Users</h4>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
                                @if(is_array($user->address))
                                    {{ implode(', ', array_filter($user->address)) }}
                                @else
                                    {{ $user->address ?? 'N/A' }}
                                @endif
                                
                                @if($user->city || $user->state || $user->pincode)
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
