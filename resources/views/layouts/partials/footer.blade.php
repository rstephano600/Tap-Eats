<footer class="bg-white border-top py-3 mt-auto">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <span class="text-muted small">&copy; {{ date('Y') }} <strong>TapEats</strong>. Built with ❤️ for Foodies.</span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                @if(Auth::check())
                <span class="badge bg-dark text-darkblue border">
                    <i class="bi bi-person-check-fill me-1 text-accent"></i> 
                    Logged in as: {{ Auth::user()->username }}
                </span>
                @endif
            </div>
        </div>
    </div>
</footer>