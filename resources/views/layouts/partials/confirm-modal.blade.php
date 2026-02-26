{{-- =============================================
     GLOBAL CONFIRMATION MODAL
     Usage: add data-confirm attributes to any
     delete button anywhere in the app
============================================= --}}

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- Top colored bar --}}
            <div class="confirm-modal-bar" id="confirmModalBar"></div>

            <div class="modal-body p-4 text-center">

                {{-- Icon --}}
                <div class="confirm-modal-icon mb-3" id="confirmModalIcon">
                    <i class="bi" id="confirmModalIconInner"></i>
                </div>

                {{-- Title --}}
                <h5 class="fw-bold mb-1" id="confirmModalTitle">Are you sure?</h5>

                {{-- Message --}}
                <p class="text-muted mb-4" id="confirmModalMessage" style="font-size:0.88rem;">
                    This action cannot be undone.
                </p>

                {{-- Buttons --}}
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button"
                            class="btn btn-light px-4 rounded-3"
                            data-bs-dismiss="modal"
                            style="font-size:0.88rem;">
                        <i class="bi bi-x-lg me-1"></i>
                        <span id="confirmModalCancelText">Cancel</span>
                    </button>
                    <button type="button"
                            class="btn px-4 rounded-3 fw-semibold"
                            id="confirmModalConfirmBtn"
                            style="font-size:0.88rem;">
                        <i class="bi me-1" id="confirmModalBtnIcon"></i>
                        <span id="confirmModalConfirmText">Confirm</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Top bar */
    .confirm-modal-bar {
        height: 5px;
        width: 100%;
    }

    /* Icon circle */
    .confirm-modal-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 2rem;
        animation: iconPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    @keyframes iconPop {
        from { transform: scale(0.5); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }

    /* Type: danger */
    .confirm-type-danger .confirm-modal-bar    { background: #dc3545; }
    .confirm-type-danger .confirm-modal-icon   { background: rgba(220,53,69,0.1); color: #dc3545; }
    .confirm-type-danger #confirmModalConfirmBtn { background: #dc3545; color: #fff; border: none; }
    .confirm-type-danger #confirmModalConfirmBtn:hover { background: #b02a37; }

    /* Type: warning */
    .confirm-type-warning .confirm-modal-bar   { background: #FFA726; }
    .confirm-type-warning .confirm-modal-icon  { background: rgba(255,167,38,0.12); color: #FFA726; }
    .confirm-type-warning #confirmModalConfirmBtn { background: #FFA726; color: #001f3f; border: none; }
    .confirm-type-warning #confirmModalConfirmBtn:hover { background: #e09400; }

    /* Type: info */
    .confirm-type-info .confirm-modal-bar      { background: #0dcaf0; }
    .confirm-type-info .confirm-modal-icon     { background: rgba(13,202,240,0.1); color: #0dcaf0; }
    .confirm-type-info #confirmModalConfirmBtn { background: #0dcaf0; color: #fff; border: none; }
    .confirm-type-info #confirmModalConfirmBtn:hover { background: #0aa2c0; }

    /* Type: success */
    .confirm-type-success .confirm-modal-bar   { background: #28a745; }
    .confirm-type-success .confirm-modal-icon  { background: rgba(40,167,69,0.1); color: #28a745; }
    .confirm-type-success #confirmModalConfirmBtn { background: #28a745; color: #fff; border: none; }
    .confirm-type-success #confirmModalConfirmBtn:hover { background: #1e7e34; }

    /* Modal entrance */
    #confirmModal .modal-content {
        animation: modalSlideUp 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes modalSlideUp {
        from { transform: translateY(40px) scale(0.96); opacity: 0; }
        to   { transform: translateY(0)    scale(1);    opacity: 1; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const modal       = document.getElementById('confirmModal');
        const modalEl     = new bootstrap.Modal(modal);
        const confirmBtn  = document.getElementById('confirmModalConfirmBtn');
        const modalDialog = modal.querySelector('.modal-dialog');

        let formToSubmit  = null;
        let callbackFn    = null;

        // =============================================
        // CONFIG MAP — define types
        // =============================================
        const typeConfig = {
            danger: {
                icon:        'bi-trash3-fill',
                btnIcon:     'bi-trash3',
                title:       'Delete this record?',
                message:     'This action is permanent and cannot be undone.',
                confirmText: 'Yes, Delete',
                cancelText:  'Cancel',
            },
            warning: {
                icon:        'bi-exclamation-triangle-fill',
                btnIcon:     'bi-exclamation-triangle',
                title:       'Are you sure?',
                message:     'Please confirm you want to proceed with this action.',
                confirmText: 'Yes, Proceed',
                cancelText:  'Cancel',
            },
            info: {
                icon:        'bi-info-circle-fill',
                btnIcon:     'bi-check-lg',
                title:       'Confirm Action',
                message:     'Please confirm you want to proceed.',
                confirmText: 'Yes, Confirm',
                cancelText:  'Cancel',
            },
            success: {
                icon:        'bi-check-circle-fill',
                btnIcon:     'bi-check-lg',
                title:       'Confirm Action',
                message:     'Are you sure you want to proceed?',
                confirmText: 'Yes, Do It',
                cancelText:  'Cancel',
            },
        };

        // =============================================
        // SHOW MODAL FUNCTION (global)
        // =============================================
        window.showConfirm = function (options) {
            const type   = options.type    || 'danger';
            const config = typeConfig[type] || typeConfig.danger;

            // Apply type class
            modalDialog.className = 'modal-dialog modal-dialog-centered confirm-type-' + type;

            // Set content — use custom or fallback to config defaults
            document.getElementById('confirmModalIconInner').className = 'bi ' + (options.icon || config.icon);
            document.getElementById('confirmModalTitle').textContent   = options.title   || config.title;
            document.getElementById('confirmModalMessage').textContent = options.message || config.message;
            document.getElementById('confirmModalConfirmText').textContent = options.confirmText || config.confirmText;
            document.getElementById('confirmModalCancelText').textContent  = options.cancelText  || config.cancelText;
            document.getElementById('confirmModalBtnIcon').className = 'bi ' + (options.btnIcon || config.btnIcon);

            // Store form or callback
            formToSubmit = options.form     || null;
            callbackFn   = options.callback || null;

            modalEl.show();
        };

        // =============================================
        // CONFIRM BUTTON CLICK
        // =============================================
        confirmBtn.addEventListener('click', function () {
            modalEl.hide();
            if (formToSubmit) {
                formToSubmit.submit();
            } else if (typeof callbackFn === 'function') {
                callbackFn();
            }
        });

        // =============================================
        // AUTO-BIND — data-confirm-form attribute
        // works on ANY delete button in any table
        // =============================================
        document.querySelectorAll('[data-confirm]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                const form = btn.closest('form');

                showConfirm({
                    type:        btn.dataset.confirmType    || 'danger',
                    title:       btn.dataset.confirmTitle   || null,
                    message:     btn.dataset.confirmMessage || null,
                    confirmText: btn.dataset.confirmOk      || null,
                    cancelText:  btn.dataset.confirmCancel  || null,
                    form:        form,
                });
            });
        });

    });
</script>