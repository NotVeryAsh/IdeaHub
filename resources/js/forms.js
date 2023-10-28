$('.axios-form').click(function (event) {

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
        url = response.data.url;

        // Copy url to user's clipboard
        navigator.clipboard.writeText(url).then(function() {
            $('#status').text('Join link copied!');
        });
    });
});
