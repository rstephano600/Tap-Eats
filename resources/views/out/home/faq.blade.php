@extends('layouts.guest-layout')

@section('title', 'FAQ - TapEats')

@section('content')
<style>
    .faq-category {
        margin-bottom: 2rem;
    }

    .faq-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        overflow: hidden;
    }

    .faq-question {
        padding: 1rem 1.5rem;
        background: white;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }

    .faq-question:hover {
        background: #f9fafb;
    }

    .faq-answer {
        padding: 0 1.5rem;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s;
        background: #f9fafb;
    }

    .faq-item.active .faq-answer {
        padding: 1rem 1.5rem;
        max-height: 500px;
    }

    .faq-item.active .faq-question {
        border-bottom: 1px solid #e5e7eb;
    }

    .faq-icon {
        transition: transform 0.3s;
    }

    .faq-item.active .faq-icon {
        transform: rotate(180deg);
    }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="mb-2">Frequently Asked Questions</h1>
                <p class="text-muted">Find answers to common questions</p>
            </div>

            <!-- FAQ Categories -->
            @foreach($faqs as $category => $questions)
                <div class="faq-category">
                    <h4 class="mb-3">
                        <i class="bi bi-folder text-primary"></i> {{ $category }}
                    </h4>

                    @foreach($questions as $index => $faq)
                        <div class="faq-item" onclick="toggleFaq(this)">
                            <div class="faq-question">
                                <h6 class="mb-0">{{ $faq['question'] }}</h6>
                                <i class="bi bi-chevron-down faq-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p class="mb-0 text-muted">{{ $faq['answer'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <!-- Still Need Help -->
            <div class="card bg-primary bg-opacity-10 border-0 mt-5">
                <div class="card-body text-center py-4">
                    <h5 class="mb-2">Still need help?</h5>
                    <p class="text-muted mb-3">Our support team is here to assist you</p>
                    <a href="{{ route('customersupport') }}" class="btn btn-primary">
                        <i class="bi bi-headset"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFaq(element) {
    element.classList.toggle('active');
}
</script>
@endsection