$('.axios-form').click(function (event) {

    // Find submit button and disable it
    let submitButton = $(this).find('button[type="submit"]');

    // Get text of submit button
    let text = submitButton.text();

    // Set button ti disabled while the request is loading
    setDisabled(submitButton);

    // Prevent the form from submitting
    event.preventDefault();

    // Get the data from a html form element
    let url = $(this).attr('action');

    // Get the method from the input with name="_method"
    let method = $(this).find('input[name="_method"]').val();

    // If the method is not set, get it from the method attribute of the form
    if (method === undefined) {
        method = $(this).attr('method');
    }

    // Get the csrf token from the input with name="_token"
    let token = $(this).find('input[name="_token"]').val();

    // do an axios request to the url with the method and token and get the url attribute from the json response
    axios({
        method: method,
        url: url,
        data: {
            _token: token
        }
    }).then(function (response) {
        // Get the url from the response
        let url = response.data.url;
        let message = response.data.message;

        // Copy url to user's clipboard
        navigator.clipboard.writeText(url).then(function() {
            $('#status').text(message);
        });

        $('#create-team-link').attr('method', 'get');

        setNotDisabled($('#copy-link-button'), 'Copy Link <i class="pl-1 fa-solid fa-link"></i>');
    });
});

function setDisabled(element)
{
    element.prop('disabled', true);
    element.html('<i class="fa-solid fa-spinner fa-spin"></i> Loading...');
    element.addClass('cursor-not-allowed');
}

function setNotDisabled(element, text)
{console.log(text);
    element.prop('disabled', false);
    element.removeClass('cursor-not-allowed');
    element.html(text);
}
