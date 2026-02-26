{{--
// Success
return redirect()->route('ordersinformations')
    ->with('success', 'Order has been created successfully.');

// Error
return redirect()->back()
    ->with('error', 'Something went wrong. Please try again.');

// Warning
return redirect()->back()
    ->with('warning', 'Stock is running low on some items.');

// Info
return redirect()->route('dashboard')
    ->with('info', 'Your session will expire in 10 minutes.');
--}}

{{-- Toast Container --}}
<div class="toast-container" id="toastContainer">

    @if(session('success'))
    <div class="app-toast app-toast-success" id="toast-success">
        <div class="app-toast-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="app-toast-body">
            <div class="app-toast-title">Success</div>
            <div class="app-toast-message">{!! session('success') !!}</div>
        </div>
        <button class="app-toast-close" onclick="closeToast('toast-success')">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="app-toast-progress" id="progress-success"></div>
    </div>
    @endif

    @if(session('error'))
    <div class="app-toast app-toast-error" id="toast-error">
        <div class="app-toast-icon">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div class="app-toast-body">
            <div class="app-toast-title">Error</div>
            <div class="app-toast-message">{!! session('error') !!}</div>
        </div>
        <button class="app-toast-close" onclick="closeToast('toast-error')">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="app-toast-progress" id="progress-error"></div>
    </div>
    @endif

    @if(session('warning'))
    <div class="app-toast app-toast-warning" id="toast-warning">
        <div class="app-toast-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="app-toast-body">
            <div class="app-toast-title">Warning</div>
            <div class="app-toast-message">{!! session('warning') !!}</div>
        </div>
        <button class="app-toast-close" onclick="closeToast('toast-warning')">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="app-toast-progress" id="progress-warning"></div>
    </div>
    @endif

    @if(session('info'))
    <div class="app-toast app-toast-info" id="toast-info">
        <div class="app-toast-icon">
            <i class="bi bi-info-circle-fill"></i>
        </div>
        <div class="app-toast-body">
            <div class="app-toast-title">Info</div>
            <div class="app-toast-message">{!! session('info') !!}</div>
        </div>
        <button class="app-toast-close" onclick="closeToast('toast-info')">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="app-toast-progress" id="progress-info"></div>
    </div>
    @endif

</div>

<style>
    /* Container — fixed top right */
    .toast-container {
        position: fixed;
        top: 80px; /* below navbar */
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-width: 340px;
        max-width: 400px;
    }

    /* Base Toast */
    .app-toast {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.13), 0 2px 8px rgba(0,0,0,0.07);
        position: relative;
        overflow: hidden;
        border-left: 4px solid transparent;
        animation: toastSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    /* Slide in from right */
    @keyframes toastSlideIn {
        from {
            opacity: 0;
            transform: translateX(120px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    /* Slide out to right */
    .app-toast.hiding {
        animation: toastSlideOut 0.35s ease forwards;
    }
    @keyframes toastSlideOut {
        from {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
        to {
            opacity: 0;
            transform: translateX(120px) scale(0.95);
        }
    }

    /* Type Colors */
    .app-toast-success { border-left-color: #28a745; }
    .app-toast-error   { border-left-color: #dc3545; }
    .app-toast-warning { border-left-color: #FFA726; }
    .app-toast-info    { border-left-color: #0dcaf0; }

    /* Icon */
    .app-toast-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
        margin-top: 1px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
    }
    .app-toast-success .app-toast-icon { color: #28a745; background: rgba(40,167,69,0.1); }
    .app-toast-error   .app-toast-icon { color: #dc3545; background: rgba(220,53,69,0.1); }
    .app-toast-warning .app-toast-icon { color: #FFA726; background: rgba(255,167,38,0.12); }
    .app-toast-info    .app-toast-icon { color: #0dcaf0; background: rgba(13,202,240,0.1); }

    /* Body */
    .app-toast-body {
        flex: 1;
        min-width: 0;
    }
    .app-toast-title {
        font-weight: 700;
        font-size: 0.88rem;
        color: #1a1a2e;
        margin-bottom: 3px;
        letter-spacing: 0.2px;
    }
    .app-toast-message {
        font-size: 0.82rem;
        color: #6c757d;
        line-height: 1.45;
    }

    /* Close Button */
    .app-toast-close {
        background: transparent;
        border: none;
        color: #adb5bd;
        cursor: pointer;
        padding: 0;
        font-size: 0.75rem;
        flex-shrink: 0;
        margin-top: 2px;
        line-height: 1;
        transition: color 0.2s;
    }
    .app-toast-close:hover {
        color: #495057;
    }

    /* Progress Bar */
    .app-toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        border-radius: 0 0 14px 14px;
        animation: toastProgress 5s linear forwards;
    }
    .app-toast-success .app-toast-progress { background: #28a745; }
    .app-toast-error   .app-toast-progress { background: #dc3545; }
    .app-toast-warning .app-toast-progress { background: #FFA726; }
    .app-toast-info    .app-toast-progress { background: #0dcaf0; }

    @keyframes toastProgress {
        from { width: 100%; }
        to   { width: 0%; }
    }

    /* Mobile */
    @media (max-width: 576px) {
        .toast-container {
            top: auto;
            bottom: 20px;
            right: 12px;
            left: 12px;
            min-width: unset;
            max-width: unset;
        }
    }
</style>

<script>
    // Auto-dismiss after 5 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const toasts = document.querySelectorAll('.app-toast');

        toasts.forEach(function (toast) {
            // Auto hide after 5s
            setTimeout(function () {
                closeToast(toast.id);
            }, 5000);
        });
    });

    function closeToast(id) {
        const toast = document.getElementById(id);
        if (!toast) return;

        toast.classList.add('hiding');

        setTimeout(function () {
            toast.style.display = 'none';
        }, 350);
    }
</script>