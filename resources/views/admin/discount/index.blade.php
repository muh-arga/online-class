@extends('layouts.app')

@section('content')
    <section class="dashboard my-5">
        <div class="container">
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="card">
                        <div class="card-header">
                            Discounts
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-12 d-flex flex-row-reverse">
                                    <a href="{{ route('admin.discount.create') }}" class="btn btn-primary btn-sm">Add
                                        Discount</a>
                                </div>
                            </div>
                            @include('components.alert')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Percentage</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($discounts as $discount)
                                        <tr>
                                            <td>{{ $discount->name }}</td>
                                            <td><span class="badge bg-primary">{{ $discount->code }}</span></td>
                                            <td>{{ $discount->description }}</td>
                                            <td>{{ $discount->percentage }}%</td>
                                            <td>
                                                <div class="d-flex flex-row justify-content-start gap-2">
                                                    <a href="{{ route('admin.discount.edit', $discount->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <form action="{{ route('admin.discount.destroy', $discount->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"> No Discount Creted </td>
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
