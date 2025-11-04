<style>
    .modal-content {
        border-radius: 1rem!important;
        max-width: 450px!important;
    }
    .modal .spinner-border {
        animation-duration: 0.8s;
    }
</style>

@props([
    'message' => 'Uploading... Please wait'
])
<!-- Enhanced Progress Modal -->
<div class="modal fade"
     id="progressModal"
     tabindex="-1"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3 border-0">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <h5 class="mb-2">{{ $message }}</h5>
                <p class="text-muted mb-4">Please wait while we process your request</p>
                <div class="progress" style="height: 24px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                         role="progressbar"
                         style="width: 0%; font-weight: bold;"
                         id="formProgress">
                        0%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
