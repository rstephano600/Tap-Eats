@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">My Profile</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST"
          action="{{ $profile ? route('customer.profile.update') : route('customer.profile.store') }}">
        @csrf
        @if($profile) @method('PUT') @endif

        <div class="row g-3">

            <div class="col-md-6">
                <label>First Name</label>
                <input type="text" name="first_name"
                       value="{{ old('first_name', $profile->first_name ?? '') }}"
                       class="form-control" required>
            </div>

            <div class="col-md-6">
                <label>Last Name</label>
                <input type="text" name="last_name"
                       value="{{ old('last_name', $profile->last_name ?? '') }}"
                       class="form-control" required>
            </div>

            <div class="col-md-4">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth"
                       value="{{ old('date_of_birth', optional($profile)->date_of_birth?->format('Y-m-d')) }}"
                       class="form-control">
            </div>

            <div class="col-md-4">
                <label>Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select</option>
                    @foreach(['male','female','prefer_not_to_say'] as $gender)
                        <option value="{{ $gender }}"
                            @selected(old('gender', $profile->gender ?? '') === $gender)>
                            {{ ucfirst(str_replace('_',' ', $gender)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Default Payment</label>
                <input type="text" name="default_payment_method"
                       value="{{ old('default_payment_method', $profile->default_payment_method ?? '') }}"
                       class="form-control">
            </div>

            <div class="col-md-6">
                <label>Dietary Preferences</label>
                <select name="dietary_preferences[]" multiple class="form-select">
                    @foreach(['vegetarian','vegan','halal','kosher'] as $item)
                        <option value="{{ $item }}"
                            @selected(in_array($item, old('dietary_preferences', $profile->dietary_preferences ?? [])))>
                            {{ ucfirst($item) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label>Allergies</label>
                <select name="allergies[]" multiple class="form-select">
                    @foreach(['nuts','dairy','seafood','eggs'] as $item)
                        <option value="{{ $item }}"
                            @selected(in_array($item, old('allergies', $profile->allergies ?? [])))>
                            {{ ucfirst($item) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 form-check mt-4">
                <input type="checkbox" name="email_notifications" value="1"
                       class="form-check-input"
                       @checked(old('email_notifications', $profile->email_notifications ?? true))>
                <label class="form-check-label">Email Notifications</label>
            </div>

            <div class="col-md-4 form-check mt-4">
                <input type="checkbox" name="sms_notifications" value="1"
                       class="form-check-input"
                       @checked(old('sms_notifications', $profile->sms_notifications ?? true))>
                <label class="form-check-label">SMS Notifications</label>
            </div>

            <div class="col-md-4 form-check mt-4">
                <input type="checkbox" name="push_notifications" value="1"
                       class="form-check-input"
                       @checked(old('push_notifications', $profile->push_notifications ?? true))>
                <label class="form-check-label">Push Notifications</label>
            </div>

            <div class="col-12 mt-4">
                <button class="btn btn-primary">
                    {{ $profile ? 'Update Profile' : 'Create Profile' }}
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
