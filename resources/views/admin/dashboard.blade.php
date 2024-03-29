@extends('layouts.app')

@section('content')
    <section class="dashboard my-5">
        <div class="container">
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="card">
                        <div class="card-header">
                            My Camps
                        </div>
                        <div class="card-body">
                            @include('components.alert')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Camp</th>
                                        <th>Price</th>
                                        <th>Register Data</th>
                                        <th>Paid Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($checkouts as $checkout)
                                        <tr>
                                            <td>{{ $checkout->User->name }}</td>
                                            <td>{{ $checkout->Camp->title }}</td>
                                            <td>
                                                <strong class="d-flex flex-row align-items-center gap-2">
                                                    Rp{{ $checkout->total }}
                                                    @if ($checkout->discount_id)
                                                        <span class="badge bg-success">{{ $checkout->discount_precentage }}%
                                                            OFF</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>{{ $checkout->created_at->format('M d Y') }}</td>
                                            <td>
                                                <strong>{{ $checkout->payment_status }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"> No Camps Found </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
