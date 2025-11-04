// public/asset/js/progress-uploader.js

window.ProgressUploader = {
    init: function () {
        $(document).on('submit', '.use-progress', this.handleSubmit);
    },

    handleSubmit: function (e) {
        e.preventDefault();

        const form = this;
        const namespace = $(form).data('namespace') || 'FormUtils';
        const validateMethod = $(form).data('validate') || 'validateUploadForm';
        const ns = window[namespace];

        // Run validation
        if (ns && typeof ns[validateMethod] === 'function') {
            const isValid = ns[validateMethod]();
            if (!isValid) return;
        }

        const formData = new FormData(form);

        // Show modal and fake progress
        $('#progressModal').modal({ backdrop: 'static', keyboard: false }).modal('show');
        $('#formProgress').css('width', '0%').text('0%');

        let percent = 0;
        const fakeProgress = setInterval(() => {
            if (percent < 90) {
                percent += 5;
                $('#formProgress').css('width', percent + '%').text(percent + '%');
            }
        }, 300);

        $.ajax({
            url: form.action,
            method: $(form).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                clearInterval(fakeProgress);
                $('#formProgress').css('width', '100%').text('100%');

                setTimeout(() => {
                    window.location.href = response.redirect_url || "/";
                }, 1000);
            },
            error: function (xhr) {
                clearInterval(fakeProgress);
                $('#progressModal').modal('hide');

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    const fieldRef = { first: null };

                    for (let field in errors) {
                        const errorId = `${field}-error`;
                        const message = errors[field][0];
                        if (typeof ns?.showError === 'function') {
                            ns.showError(errorId, message, fieldRef);
                        }
                    }

                    if (fieldRef.first) {
                        fieldRef.first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    alert('Upload failed. Please try again.');
                }
            }
        });
    }
};
