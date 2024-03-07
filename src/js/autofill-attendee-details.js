(function ($) {
    'use strict';

    // Define the selectors once, prevent repeated DOM queries
    const selectors = {
        submitButton: '#tribe-tickets__tickets-submit',
        removeButton: '.tribe-tickets__attendee-tickets-item-remove',
        nameInput: 'input[name*="[attendees][1][meta][tribe-tickets-plus-iac-name]"]',
        emailInput: 'input[name*="[attendees][1][meta][tribe-tickets-plus-iac-email]"]'
    };

    // Initialize function to set up listeners and handlers
    function init() {
        if (typeof current_user_data !== 'undefined') {
            handleModalState();
            $(document).on('click', selectors.removeButton, handleRemoveButtonClick);
        }
    }

    // Handles the modal state to decide whether to bind the submit button or call updateAttendeeData directly
    function handleModalState() {
        if (!current_user_data.modal) {
            updateAttendeeData();
        } else {
            $(selectors.submitButton).on('click', updateAttendeeData);
        }
    }

    // Observes DOM changes and updates attendee information when the inputs become available
    function updateAttendeeData() {
        if (current_user_data.name && current_user_data.email) {
            const observer = new MutationObserver((mutations, obs) => {
                if ($(selectors.nameInput).length && $(selectors.emailInput).length) {
                    updateAttendeeInfo(selectors.nameInput, current_user_data.name);
                    updateAttendeeInfo(selectors.emailInput, current_user_data.email);
                    obs.disconnect();
                }
            });

            observer.observe(document, {
                childList: true,
                subtree: true
            });
        }
    }

    // Updates the value of the input field if it exists
    function updateAttendeeInfo(fieldSelector, value) {
        const inputField = $(fieldSelector);
        if (inputField.length) {
            inputField.val(value);
        }
    }

    // Checks and populates the fields if they are empty and visible
    function checkAndPopulateFields() {
        if ($(selectors.nameInput).is(':visible') && !$(selectors.nameInput).val()) {
            updateAttendeeInfo(selectors.nameInput, current_user_data.name);
        }
        if ($(selectors.emailInput).is(':visible') && !$(selectors.emailInput).val()) {
            updateAttendeeInfo(selectors.emailInput, current_user_data.email);
        }
    }

    // Handle remove button click and set a delay to ensure fields are ready
    function handleRemoveButtonClick() {
        setTimeout(checkAndPopulateFields, 100);
    }

    // Initialize the script
    $(document).ready(init);

})(jQuery);
