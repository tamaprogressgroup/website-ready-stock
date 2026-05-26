<script>
 function getUrlParams() {
        const params = {};
        const queryString = window.location.search.substring(1);
        const pairs = queryString.split('&');

        for (let i = 0; i < pairs.length; i++) {
            const pair = pairs[i].split('=');
            if (pair.length === 2) {
                params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
        }

        return params;
    }

    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    function setupContactForm(formId) {
        const $form = $('#' + formId);

        $form.on('submit', function (e) {
            e.preventDefault();
            grecaptcha.ready(function () {
                grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'submit'}).then(function (token) {
                    $form.find('button').prop('disabled', true).prepend('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
                    const formData = $form.serializeArray();
                    const hubspotutk = getCookie('hubspotutk');
                     const urlParams = getUrlParams();
                    for (const key in urlParams) {
                        formData.push({name: key, value: urlParams[key]});
                    }
                    formData.push({ name: 'hubspotutk', value: hubspotutk });
                    formData.push({name: 'url_form', value: window.location.href});
                    formData.push({name: 'recaptcha_token', value: token});
                    formData.push({name: 'contact_form_id', value: formId});
                    formData.push({name: 'commercial', value:'{{ $commercial_redis['commercial_id'] }}'});
                    formData.push({name: 'unit', value: '{{ isset($unitType_redis['unit_id']) ?  $unitType_redis['unit_id'] :  '' }}'});

                    $.ajax({
                        url: '/contactus/submit',
                        method: 'POST',
                        data: $.param(formData),
                        success: function (data) {
                            $form[0].reset();
                            if (formId === 'whatsappcontactform') {
                                dataLayer.push({'event': 'submit_form_whatsapp'});
                                if (data.existing_contact) {
                                    window.location.href = "https://api.whatsapp.com/send?phone="+data.wa_no+"&text="+pree_filled_message;
                                } else {
                                    window.location.href = "https://api.whatsapp.com/send?phone="+wa_no+"&text="+pree_filled_message;
                                }
                            } else {
                                 window.location.href = thankyoupage
                            }


                        },
                        error: function (xhr) {
                            const response = xhr.responseJSON;
                            $form.find('.is-invalid').removeClass('is-invalid');
                            $form.find('.invalid-feedback').text('');
                            if (response?.errors) {
                                for (const field in response.errors) {
                                    const message = response.errors[field][0];
                                    const $input = $form.find(`[name="${field}"]`);
                                    $input.addClass('is-invalid');
                                    const $feedback = $input.siblings('.invalid-feedback');
                                    if ($feedback.length) {
                                        $feedback.text(message);
                                    }
                                }
                            } else {
                                console.log(response.message || 'Gagal mengirim.');
                            }
                        },
                        complete: function () {
                            $form.find('button').prop('disabled', false)
                            $form.find('.spinner-border').remove();
                        }

                    });
                });
            });
        });
    }
</script>