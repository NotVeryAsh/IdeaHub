const form = $('#recaptcha-protected-form');
const submitButton = form.find("button[type=submit]");

submitButton.on('click', function(event) {

    // Prevent form submission
    event.preventDefault();

    // get site key and action
    let siteKey = form.data('sitekey');

    if(!siteKey) {
        console.error("reCAPTCHA sitekey not found");
        return;
    }

    let action = form.data('action');

    if(!action) {
        console.error("reCAPTCHA action not found");
        return;
    }

    // execute reCAPTCHA
    grecaptcha.ready(function() {
        grecaptcha.execute(siteKey, { action: action }).then(function(token) {

            // set token and action
            $('#recaptcha_response').val(token);
            $('#recaptcha_action').val(action);

            // Submit form
            form.submit();
        });
    });
});
