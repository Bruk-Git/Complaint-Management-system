$(document).ready(function(){

    // ----------------------------------
    // STEP 1: LOGIN DETAILS VALIDATION
    // ----------------------------------
    $('#btn_login_details').click(function(){

        var error_email = '';
        var error_password = '';
        var error_confirm_password = '';

        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        // Email validation
        if ($.trim($('#email').val()).length == 0) {
            error_email = 'Email is required';
            $('#error_email').text(error_email);
            $('#email').addClass('has-error');
        } else if (!filter.test($('#email').val())) {
            error_email = 'Invalid Email';
            $('#error_email').text(error_email);
            $('#email').addClass('has-error');
        } else {
            error_email = '';
            $('#error_email').text('');
            $('#email').removeClass('has-error');
        }

        // Password validation
        if ($.trim($('#password').val()).length == 0) {
    error_password = 'Password is required';
    $('#error_password').text(error_password);
    $('#password').addClass('has-error');
} else {
    const password = $('#password').val();
    const errors = [];
    
    // Check minimum length
    if (password.length < 8) {
        errors.push('Minimum 8 characters required');
    }
    
    // Check for uppercase letter
    if (!/[A-Z]/.test(password)) {
        errors.push('At least one uppercase letter (A-Z)');
    }
    
    // Check for lowercase letter
    if (!/[a-z]/.test(password)) {
        errors.push('At least one lowercase letter (a-z)');
    }
    
    // Check for number
    if (!/[0-9]/.test(password)) {
        errors.push('At least one number (0-9)');
    }
    
    // Check for special character
    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
        errors.push('At least one special character (!@#$%^&* etc.)');
    }
    
    if (errors.length > 0) {
        error_password = 'Weak password. Requirements:<br>' + errors.join('<br>');
        $('#error_password').html(error_password);
        $('#password').addClass('has-error');
    } else {
        error_password = '';
        $('#error_password').text('');
        $('#password').removeClass('has-error');
        $('#password').addClass('has-success');
    }
}

        // Confirm Password validation
        if ($.trim($('#confirm_password').val()).length == 0) {
            error_confirm_password = 'Confirm your password';
            $('#error_confirm_password').text(error_confirm_password);
            $('#confirm_password').addClass('has-error');
        } else if ($('#confirm_password').val() !== $('#password').val()) {
            error_confirm_password = 'Passwords do not match';
            $('#error_confirm_password').text(error_confirm_password);
            $('#confirm_password').addClass('has-error');
        } else {
            error_confirm_password = '';
            $('#error_confirm_password').text('');
            $('#confirm_password').removeClass('has-error');
        }

        // Stop and do not go next tab
        if (error_email != '' || error_password != '' || error_confirm_password != '') {
            return false;
        }

        // Move to next tab
        $('#list_login_details').removeClass('active active_tab1').addClass('inactive_tab1');
        $('#login_details').removeClass('active in');

        $('#list_personal_details')
            .removeClass('inactive_tab1')
            .addClass('active_tab1 active')
            .attr('href', '#personal_details')
            .attr('data-toggle', 'tab');

        $('#personal_details').addClass('active in');
    });


    // ----------------------------------
    // STEP 2: PERSONAL DETAILS VALIDATION
    // ----------------------------------
    $('#previous_btn_personal_details').click(function(){
        $('#list_personal_details').removeClass('active active_tab1')
        .addClass('inactive_tab1').removeAttr('href data-toggle');

        $('#personal_details').removeClass('active in');

        $('#list_login_details').removeClass('inactive_tab1').addClass('active_tab1 active')
        .attr('href', '#login_details').attr('data-toggle', 'tab');

        $('#login_details').addClass('active in');
    });

    $('#btn_personal_details').click(function(){

        var error_first_name = '';
        var error_last_name = '';
        var error_gender = '';
        var error_student_id = '';

        // First name
        if ($.trim($('#first_name').val()).length == 0) {
            error_first_name = 'First Name is required';
            $('#error_first_name').text(error_first_name);
            $('#first_name').addClass('has-error');
        } else {
            $('#error_first_name').text('');
            $('#first_name').removeClass('has-error');
        }

        // Last name
        if ($.trim($('#last_name').val()).length == 0) {
            error_last_name = 'Last Name is required';
            $('#error_last_name').text(error_last_name);
            $('#last_name').addClass('has-error');
        } else {
            $('#error_last_name').text('');
            $('#last_name').removeClass('has-error');
        }

        // Student ID
        if ($.trim($('#student_id').val()).length == 0) {
            error_student_id = 'Student ID is required';
            $('#error_student_id').text(error_student_id);
            $('#student_id').addClass('has-error');
        } else {
            $('#error_student_id').text('');
            $('#student_id').removeClass('has-error');
        }

        if (error_first_name != '' || error_last_name != '' || error_student_id != '') {
            return false;
        }

        // Move to next tab
        $('#list_personal_details').removeClass('active active_tab1').addClass('inactive_tab1');
        $('#personal_details').removeClass('active in');

        $('#list_contact_details')
        .removeClass('inactive_tab1')
        .addClass('active_tab1 active')
        .attr('href', '#contact_details')
        .attr('data-toggle', 'tab');

        $('#contact_details').addClass('active in');
    });


    // ----------------------------------
    // STEP 3: CONTACT + ACADEMIC DETAILS
    // ----------------------------------
    $('#previous_btn_contact_details').click(function(){
        $('#list_contact_details').removeClass('active active_tab1')
        .addClass('inactive_tab1').removeAttr('href data-toggle');

        $('#contact_details').removeClass('active in');

        $('#list_personal_details')
        .removeClass('inactive_tab1')
        .addClass('active_tab1 active')
        .attr('href', '#personal_details')
        .attr('data-toggle', 'tab');

        $('#personal_details').addClass('active in');
    });


    $('#btn_contact_details').click(function(){

        var error_department = '';
        var error_year = '';
        var error_address = '';
        var error_mobile_no = '';

        var mobile_validation = /^\d{10}$/;

        // Department
        if ($('#department').val() == "") {
            error_department = 'Select your department';
            $('#error_department').text(error_department);
            $('#department').addClass('has-error');
        } else {
            $('#error_department').text('');
            $('#department').removeClass('has-error');
        }

        // Year
        if ($('#year').val() == "") {
            error_year = 'Select academic year';
            $('#error_year').text(error_year);
            $('#year').addClass('has-error');
        } else {
            $('#error_year').text('');
            $('#year').removeClass('has-error');
        }

        // Address
        if ($.trim($('#address').val()).length == 0) {
            error_address = 'Address is required';
            $('#error_address').text(error_address);
            $('#address').addClass('has-error');
        } else {
            $('#error_address').text('');
            $('#address').removeClass('has-error');
        }

        // Mobile number
       if ($.trim($('#mobile_no').val()).length == 0) {
    error_mobile_no = 'Mobile number is required';
    $('#error_mobile_no').text(error_mobile_no);
    $('#mobile_no').addClass('has-error');
} else {
    const mobileNo = $('#mobile_no').val().trim();
    
    // Remove any spaces, dashes, or other characters
    const cleanMobile = mobileNo.replace(/[\s\-\(\)\.]/g, '');
    
    // Ethiopian mobile number rules:
    // 1. Must start with 09 or 07
    // 2. Must be exactly 10 digits total
    // 3. Must contain only numbers
    const ethiopianMobileRegex = /^(09|07)[0-9]{8}$/;
    
    if (!/^[0-9]+$/.test(cleanMobile)) {
        error_mobile_no = 'Mobile number should contain only digits';
        $('#error_mobile_no').text(error_mobile_no);
        $('#mobile_no').addClass('has-error');
    } else if (cleanMobile.length !== 10) {
        error_mobile_no = 'Mobile number must be exactly 10 digits (e.g., 0912345678)';
        $('#error_mobile_no').text(error_mobile_no);
        $('#mobile_no').addClass('has-error');
    } else if (!/^(09|07)/.test(cleanMobile)) {
        error_mobile_no = 'Ethiopian mobile numbers must start with 09 or 07';
        $('#error_mobile_no').text(error_mobile_no);
        $('#mobile_no').addClass('has-error');
    } else if (!ethiopianMobileRegex.test(cleanMobile)) {
        error_mobile_no = 'Invalid mobile number format. Should be: 09xxxxxxxx or 07xxxxxxxx';
        $('#error_mobile_no').text(error_mobile_no);
        $('#mobile_no').addClass('has-error');
    } else {
        error_mobile_no = '';
        $('#error_mobile_no').text('');
        $('#mobile_no').removeClass('has-error').addClass('has-success');
        
        // Optional: Auto-format the number for display
        $('#mobile_no').val(formatEthiopianMobile(cleanMobile));
    }
}

// Optional formatting function
function formatEthiopianMobile(mobile) {
    // Format as: 09XX XXX XXXX or 07XX XXX XXXX
    return mobile.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
}
        if (error_department != '' || error_year != '' || error_address != '' || error_mobile_no != '') {
            return false;
        }

        $('#btn_contact_details').attr("disabled", "disabled");
        $(document).css('cursor', 'progress');
        $("#register_form").submit();
    });

});
