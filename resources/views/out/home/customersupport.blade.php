@extends('layouts.guest-layout')

@section('title', 'Customer Support - TapEats')

@section('content')
<style>
    .support-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s;
        height: 100%;
    }

    .support-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .support-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .ticket-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-open { background: #dbeafe; color: #1e40af; }
    .status-in_progress { background: #fef3c7; color: #92400e; }
    .status-resolved { background: #d1fae5; color: #065f46; }
    .status-closed { background: #e5e7eb; color: #6b7280; }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="mb-2">How can we help you?</h1>
                <p class="text-muted">We're here to assist you with any questions or concerns</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Quick Support Options -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="support-card text-center">
                        <div class="support-icon bg-primary bg-opacity-10 text-primary mx-auto">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <h5 class="mb-2">Call Us</h5>
                        <p class="text-muted mb-3">Speak to our support team</p>
                        <a href="tel:+255657856790" class="btn btn-outline-primary">
                            <i class="bi bi-telephone"></i> +255 123 456 789
                        </a>
                        <p class="text-muted small mt-2 mb-0">Mon-Sun: 8AM - 10PM</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="support-card text-center">
                        <div class="support-icon bg-success bg-opacity-10 text-success mx-auto">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <h5 class="mb-2">WhatsApp</h5>
                        <p class="text-muted mb-3">Chat with us instantly</p>
                        <a href="https://wa.me/255657856790" target="_blank" class="btn btn-outline-success">
                            <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                        </a>
                        <p class="text-muted small mt-2 mb-0">Quick responses</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="support-card text-center">
                        <div class="support-icon bg-info bg-opacity-10 text-info mx-auto">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h5 class="mb-2">Email</h5>
                        <p class="text-muted mb-3">Send us a detailed message</p>
                        <a href="mailto:rstephano600@gmail.com" class="btn btn-outline-info">
                            <i class="bi bi-envelope"></i> ejossolutions@info.co.tz
                        </a>
                        <p class="text-muted small mt-2 mb-0">Reply within 24 hours</p>
                    </div>
                </div>
            </div>

            <!-- Submit Support Ticket -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="mb-4">
                        <i class="bi bi-ticket-perforated"></i> Submit a Support Ticket
                    </h4>

                    <form action="{{ route('submitsupportticket') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <!-- Customer Information -->
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control" 
                                       value="{{ Auth::check() ? Auth::user()->name : old('customer_name') }}" 
                                       required>
                                @error('customer_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control" 
                                       value="{{ Auth::check() ? Auth::user()->email : old('customer_email') }}" 
                                       required>
                                @error('customer_email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="customer_phone" class="form-control" 
                                       value="{{ Auth::check() ? Auth::user()->phone : old('customer_phone') }}">
                                @error('customer_phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Related Order (Optional)</label>
                                <select name="order_number" class="form-select">
                                    <option value="">-- Select an order --</option>
                                    @foreach($recentOrders as $order)
                                        <option value="{{ $order->order_number }}" {{ old('order_number') == $order->order_number ? 'selected' : '' }}>
                                            {{ $order->order_number }} - ${{ number_format($order->total_amount, 2) }} 
                                            ({{ $order->created_at->format('M d, Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_number')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Issue Details -->
                            <div class="col-md-6">
                                <label class="form-label">Issue Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="order_issue" {{ old('category') == 'order_issue' ? 'selected' : '' }}>Order Issue</option>
                                    <option value="delivery_issue" {{ old('category') == 'delivery_issue' ? 'selected' : '' }}>Delivery Issue</option>
                                    <option value="payment_issue" {{ old('category') == 'payment_issue' ? 'selected' : '' }}>Payment Issue</option>
                                    <option value="food_quality" {{ old('category') == 'food_quality' ? 'selected' : '' }}>Food Quality</option>
                                    <option value="missing_items" {{ old('category') == 'missing_items' ? 'selected' : '' }}>Missing Items</option>
                                    <option value="wrong_order" {{ old('category') == 'wrong_order' ? 'selected' : '' }}>Wrong Order</option>
                                    <option value="refund_request" {{ old('category') == 'refund_request' ? 'selected' : '' }}>Refund Request</option>
                                    <option value="account_issue" {{ old('category') == 'account_issue' ? 'selected' : '' }}>Account Issue</option>
                                    <option value="general_inquiry" {{ old('category') == 'general_inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" 
                                       placeholder="Brief summary of your issue" 
                                       value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" rows="5" 
                                          placeholder="Please provide detailed information about your issue..." 
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Attachments (Optional)</label>
                                <input type="file" name="attachments[]" class="form-control" 
                                       multiple accept="image/*,application/pdf">
                                <small class="text-muted">You can upload images or PDF files (Max 2MB each)</small>
                                @error('attachments.*')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send"></i> Submit Ticket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Tickets -->
            @if($recentTickets->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">
                            <i class="bi bi-clock-history"></i> Your Recent Tickets
                        </h4>

                        <div class="list-group list-group-flush">
                            @foreach($recentTickets as $ticket)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-fill">
                                            <h6 class="mb-1">{{ $ticket->subject }}</h6>
                                            <p class="text-muted small mb-2">
                                                Ticket #{{ $ticket->ticket_number }} • 
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </p>
                                            <span class="ticket-badge status-{{ $ticket->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- FAQ Link -->
            <div class="text-center mt-4">
                <p class="text-muted">Looking for quick answers?</p>
                <a href="{{ route('supportfaq') }}" class="btn btn-outline-primary">
                    <i class="bi bi-question-circle"></i> Browse FAQ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection