@extends('layouts.guest-layout')

@section('title', 'Contact Us - TapEats')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Get in Touch</h1>
        <p class="text-muted">Have a question about an order or want to partner with us? We're here to help.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary-soft p-3 rounded-circle me-3">
                            <i class="bi bi-envelope-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Email Us</h6>
                            <a href="mailto:{{ $contacts['email'] }}" class="text-decoration-none text-muted">{{ $contacts['email'] }}</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success-soft p-3 rounded-circle me-3">
                            <i class="bi bi-whatsapp text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">WhatsApp</h6>
                            <a href="https://wa.me/{{ $contacts['whatsapp'] }}" target="_blank" class="text-decoration-none text-muted">Chat with us</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-info-soft p-3 rounded-circle me-3">
                            <i class="bi bi-telephone-fill text-info fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Call Us</h6>
                            <p class="mb-0 text-muted">{{ $contacts['phone'] }}</p>
                        </div>
                    </div>

                    <hr>
                    
                    <h6 class="fw-bold mb-3">Follow Our Journey</h6>
                    <div class="d-flex gap-3">
                        <a href="{{ $contacts['socials']['facebook'] }}" class="btn btn-outline-primary btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                        <a href="{{ $contacts['socials']['instagram'] }}" class="btn btn-outline-danger btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                        <a href="{{ $contacts['socials']['twitter'] }}" class="btn btn-outline-info btn-sm rounded-circle"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4">
                <form action="#" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Your Name</label>
                            <input type="text" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <select class="form-select">
                                <option selected>General Inquiry</option>
                                <option>Order Support</option>
                                <option>Become a Supplier</option>
                                <option>Technical Issue</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4 py-2">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
</style>
@endsection