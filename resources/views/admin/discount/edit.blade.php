@extends('layouts.app')

@section('content')
    <section class="dashboard my-5">
        <div class="container">
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="card">
                        <div class="card-header">
                            Update Discount {{ $discount->name }}
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.discount.update', $discount->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $discount->id }}">
                                <div class="form-group mb-4">
                                    <label class="form-label" for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $discount->name) }}" placeholder="Enter Name" required>
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label" for="code">Code</label>
                                    <input type="text" name="code" id="code" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" value="{{ old('code', $discount->code) }}" placeholder="Enter Code" required>
                                    @if ($errors->has('code'))
                                        <span class="text-danger">{{ $errors->first('code') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label" for="description">Descsription</label>
                                    <textarea name="description" id="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" placeholder="Enter Description">{{ old('description', $discount->description) }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label" for="percentage">Disscsount Percentage</label>
                                    <input type="number" min="1" max="100" name="percentage" id="percentage" class="form-control {{ $errors->has('percentage') ? 'is-invalid' : '' }}" value="{{ old('percentage', $discount->percentage) }}" required>
                                    @if ($errors->has('percentage'))
                                        <span class="text-danger">{{ $errors->first('percentage') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-4">
                                   <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
