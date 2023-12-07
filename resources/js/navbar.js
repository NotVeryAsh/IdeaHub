const triggers = getTriggers();
const menus = getMenus();

// Get all toggle-able menus
function getMenus()
{
    let targets = [];

    triggers.each(function() {
        let target = $(this).data('collapse-toggle');

        target = $('#' + target);

        targets.push(target);
    });

    return targets;
}

// Get all triggers for toggle-able menus
function getTriggers(){
    return $('button[data-collapse-toggle]');
}

// Hide all menus if anything other than the menu is clicked
$(document).click(function(event){
    hideOtherElements(event.target, menus);
});

// Toggle navbar visibility when its trigger has been clicked
triggers.click(function (event) {

    // Prevent the document click event from firing
    event.stopPropagation();

    // Get target on the burger menu button
    let target = $(this).data('collapse-toggle');

    // Get the html element with this id
    target = $('#' + target);

    hideOtherElements(target, menus);

    toggle(target);
});

// Hide all menus, other than the one that was clicked
function hideOtherElements(element, otherElements) {

    $.each(otherElements, function (index, value) {

        // Check if the menu contains the clicked element
        const hasElement = $(value).has(element).length === 1;

        // Check if the menu has input elements which are not hidden
        const hasInputs = $(value).has("input[type!='hidden']").length === 1;

        // Check if the menu itself is clicked
        const isMenuClicked = value.is(element);

        // If a menu with none-hidden inputs is clicked, return and don't hide the menu so we don't hide the form and
        // potentially disrupt the user if they are filling the form out
        if(hasElement && hasInputs) {
            return;
        }

        // Check if an element other than the menu is clicked, so we know the user is clicking away from the menu
        if (!isMenuClicked) {
            HideElement(value);
        }
    })
}

// Toggle visibility of an element
window.toggle = function toggleElementVisibility(element) {
    // Toggle visibility this way (using toggle causes menu to stay hidden when resizing the window)
    if(!element.hasClass('hidden')) {
        HideElement(element);
    } else {
        showElement(element);
    }
}

// Hide an element
function HideElement(element) {
    element.addClass('hidden');
}

// Show an element
function showElement(element) {
    element.removeClass('hidden');
}
