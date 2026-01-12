@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">
        {{ isset($address) ? 'Edit Address' : 'Add Address' }}
    </h4>

    <form method="POST"
          action="{{ isset($address)
                ? route('customer.addresses.update', $address)
                : route('customer.addresses.store') }}">
        @csrf
        @if(isset($address)) @method('PUT') @endif

        <div class="row g-3">

            <div class="col-md-4">
                <label>Address Type</label>
                <select name="address_type" class="form-select">
                    @foreach(['home','work','other'] as $type)
                        <option value="{{ $type }}"
                            @selected(old('address_type', $address->address_type ?? 'home') === $type)>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Label</label>
                <input type="text" name="label"
                       class="form-control"
                       value="{{ old('label', $address->label ?? '') }}">
            </div>

            <div class="col-md-4 form-check mt-4">
                <input type="checkbox" name="is_default" value="1"
                       class="form-check-input"
                       @checked(old('is_default', $address->is_default ?? false))>
                <label class="form-check-label">Set as Default</label>
            </div>

            <div class="col-md-12">
                <label>Address Line 1</label>
                <input type="text" name="address_line1"
                       class="form-control" required
                       value="{{ old('address_line1', $address->address_line1 ?? '') }}">
            </div>

            <div class="col-md-12">
                <label>Address Line 2</label>
                <input type="text" name="address_line2"
                       class="form-control"
                       value="{{ old('address_line2', $address->address_line2 ?? '') }}">
            </div>

            <div class="col-md-4">
                <label>City</label>
                <input type="text" name="city" class="form-control" required
                       value="{{ old('city', $address->city ?? '') }}">
            </div>

            <div class="col-md-4">
                <label>Postal Code</label>
                <input type="text" name="postal_code" class="form-control" required
                       value="{{ old('postal_code', $address->postal_code ?? '') }}">
            </div>

            <div class="col-md-4">
                <label>Country</label>
                <input type="text" name="country" class="form-control"
                       value="{{ old('country', $address->country ?? 'Tanzania') }}">
            </div>

            <div class="col-md-6">
                <label>Latitude</label>
                <input type="text" name="latitude" class="form-control" required
                       value="{{ old('latitude', $address->latitude ?? '') }}">
            </div>

            <div class="col-md-6">
                <label>Longitude</label>
                <input type="text" name="longitude" class="form-control" required
                       value="{{ old('longitude', $address->longitude ?? '') }}">
            </div>

            <div class="col-md-12">
                <label>Delivery Instructions</label>
                <textarea name="delivery_instructions" class="form-control"
                          rows="3">{{ old('delivery_instructions', $address->delivery_instructions ?? '') }}</textarea>
            </div>

            <div class="col-12">
                <button class="btn btn-primary">
                    {{ isset($address) ? 'Update Address' : 'Save Address' }}
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
