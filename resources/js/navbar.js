$('button[data-collapse-toggle]').click(function () {
    // Get target on the burger menu button
    let target = $(this).data('collapse-toggle');

    // Get the html element with this id
    let element = $('#' + target);

    // Toggle visibility this way (using toggle causes menu to stay hidden when resizing the window)
    if(element.hasClass('hidden')) {
        element.removeClass('hidden');
    } else {
        element.addClass('hidden');
    }
});
