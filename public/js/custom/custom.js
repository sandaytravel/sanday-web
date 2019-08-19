toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

var activitycheck = "";
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image-preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {

    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
    // Login
    $('#userlogin-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: email_required
                    },
                    emailAddress: {
                        message: email_invalid
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: password_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    $('#user-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: name_required
                    }
                }
            },
            email: {
                validators: {
                    remote: {
                        data: {"_token": $("input[name=_token]").val(), "action": $("#actionname").val(), 'userid': $("#user-id").val()},
                        url: userEmailExists,
                        type: 'POST',
                        message: user_email_exists
                    },
                    notEmpty: {
                        message: email_required
                    }, emailAddress: {
                        message: email_invalid
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: password_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    }
                }
            },
            password_confirmation: {
                validators: {
                    notEmpty: {
                        message: confirmpassword_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    },
                    identical: {
                        field: 'password',
                        message: password_identical
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });

    /// Merchant add form
    $('#merchant-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: name_required
                    }
                }
            },
            companyname: {
                validators: {
                    notEmpty: {
                        message: "Company name is required"
                    }
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: "Phone number is required"
                    },
                    regexp: {
                        regexp: /^[0-9-+()]*$/,
                        message: 'Only number required'
                    },
                    stringLength: {
                        min: 10,
                        max: 10,
                        message: "Phone number must be 10 digits"
                    }
                }
            },
            country: {
                validators: {
                    notEmpty: {
                        message: "Country name is required"
                    }
                },
            },
            city: {
                validators: {
                    notEmpty: {
                        message: "City name is required"
                    }
                }
            },
            website: {
                validators: {
                    notEmpty: {
                        message: "Website is required"
                    },
                    uri: {
                        message: 'Website url is not valid'
                    }
                }
            },
            sst_certificate: {
                validators: {
                    notEmpty: {
                        message: "Company SST certificate required"
                    }
                }
            },
            email: {
                validators: {
                    remote: {
                        data: {"_token": $("input[name=_token]").val(), "action": $("#actionname").val(), 'userid': $("#user-id").val()},
                        url: userEmailExists,
                        type: 'POST',
                        message: user_email_exists
                    },
                    notEmpty: {
                        message: email_required
                    }, emailAddress: {
                        message: email_invalid
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: password_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    },
                    identical: {
                        field: 'password_confirmation',
                        message: password_identical
                    }
                }
            },
            password_confirmation: {
                validators: {
                    notEmpty: {
                        message: confirmpassword_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    },
                    identical: {
                        field: 'password',
                        message: password_identical
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    $("#user-form #password").keyup(function () {
        if ($("#password_confirmation").val() != "") {
            $("#user-form").bootstrapValidator("revalidateField", 'password_confirmation');
        }
    });
    //Explore form validation
    $('#explore-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: "Explorer title required"
                    }
                }
            },
            'image[]': {
                file: {
                    extension: 'jpeg,png',
                    type: 'image/jpeg,image/png',
                    maxSize: 2048 * 1024,
                    message: 'The selected file is not valid'
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });

    // Forgot password
    $('#forgotpassword-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: email_required
                    }, emailAddress: {
                        message: email_invalid
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // Reset password
    $('#resetpassword-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            newpassword: {
                validators: {
                    notEmpty: {
                        message: newpassword_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    }
                }
            },
            confirmpassword: {
                validators: {
                    notEmpty: {
                        message: confirmpassword_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    },
                    identical: {
                        field: 'newpassword',
                        message: password_identical
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    $("#resetpassword-form #newpassword").keyup(function () {
        if ($("#confirmpassword").val() != "") {
            $("#resetpassword-form").bootstrapValidator("revalidateField", 'confirmpassword');
        }
    });
    // Change Password form
    $('#changepassword-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            oldpassword: {
                validators: {
                    notEmpty: {
                        message: oldpassword_required
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: newpassword_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    }
                }
            },
            confirmpassword: {
                validators: {
                    notEmpty: {
                        message: confirmpassword_required
                    },
                    stringLength: {
                        min: 6,
                        message: password_length
                    },
                    identical: {
                        field: 'password',
                        message: password_identical
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    $("#changepassword-form #password").keyup(function () {
        if ($("#password_confirmation").val() != "") {
            $("#changepassword-form").bootstrapValidator("revalidateField", 'password_confirmation');
        }
    });
    // Email Template
    $('#email-template-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            subject: {
                validators: {
                    notEmpty: {
                        message: subject_required
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    $('#add-countitnent-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            'continent[]': {
                validators: {
                    notEmpty: {
                        message: continent_name
                    },
                    stringLength: {
                        max: 30,
                        message: continent_strlen
                    },
                    regexp: {
                        regexp: /^[a-z\s]+$/i,
                        message: 'Continent consist of alphabetical characters and spaces only'
                    },
                    remote: {
                        data: {"_token": $("input[name=_token]").val(), "action": $("#actionname").val(), 'continent_id': $("#continent_id").val()},
                        url: continentExists,
                        type: 'POST',
                        message: continent_exists
                    }
                }
            }
        }
    }).on('click', '.addButton', function () {
        var $template = $('#optionTemplate'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $option = $clone.find('[name="continent[]"]');

        // Add new field
        $('#add-countitnent-form').bootstrapValidator('addField', $option);
    }).on('click', '.removeButton', function () {
        var $row = $(this).parents('.form-group'),
                $option = $row.find('[name="continent[]"]');

        // Remove element containing the option
        $row.remove();

        // Remove field
        $('#add-countitnent-form').bootstrapValidator('removeField', $option);
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });

    // Update Continent
    $('#update-countitnent-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            continent: {
                validators: {
                    notEmpty: {
                        message: continent_name
                    },
                    stringLength: {
                        max: 30,
                        message: continent_strlen
                    },
                    regexp: {
                        regexp: /^[a-z\s]+$/i,
                        message: 'Continent consist of alphabetical characters and spaces only'
                    },
                    remote: {
                        data: function (validator, $field, value) {
                            return {
                                continent_id: validator.getFieldElements('update_continent_id').val(),
                                action: "edit",
                                _token: $("input[name=_token]").val()
                            };
                        },
                        url: continentExists,
                        type: 'POST',
                        message: continent_exists
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    $('#add-country-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            'country[]': {
                validators: {
                    notEmpty: {
                        message: country_name
                    },
                    stringLength: {
                        max: 30,
                        message: country_strlen
                    },
                    regexp: {
                        regexp: /^[a-z\s]+$/i,
                        message: 'Country consist of alphabetical characters and spaces only'
                    },
                    remote: {
                        data: {"_token": $("input[name=_token]").val(), "action": $("#actionname").val(), 'country_id': $("#country_id").val()},
                        url: countryExists,
                        type: 'POST',
                        message: country_exists
                    }
                }
            }
        }
    }).on('click', '.addButton', function () {
        var $template = $('#countryTemplate'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $option = $clone.find('[name="country[]"]');

        // Add new field
        $('#add-country-form').bootstrapValidator('addField', $option);
    }).on('click', '.removeButton', function () {
        var $row = $(this).parents('.form-group'),
                $option = $row.find('[name="country[]"]');

        // Remove element containing the option
        $row.remove();

        // Remove field
        $('#add-country-form').bootstrapValidator('removeField', $option);
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // Update Country
    $('#update-country-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            country: {
                validators: {
                    notEmpty: {
                        message: country_name
                    },
                    stringLength: {
                        max: 30,
                        message: country_strlen
                    },
                    regexp: {
                        regexp: /^[a-z\s]+$/i,
                        message: 'Country can consist of alphabetical characters and spaces only'
                    },
                    remote: {
                        data: function (validator, $field, value) {
                            return {
                                country_id: validator.getFieldElements('country_id').val(),
                                action: "edit",
                                _token: $("input[name=_token]").val()
                            };
                        },
                        url: countryExists,
                        type: 'POST',
                        message: country_exists
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // City Form
    $('#city-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            country: {
                validators: {
                    notEmpty: {
                        message: country_d_name
                    }
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: city_name
                    },
                    stringLength: {
                        max: 30,
                        message: city_strlen
                    },
                    regexp: {
                        regexp: /^[a-z\s]+$/i,
                        message: 'City consist of alphabetical characters and spaces only'
                    }
                }
            },
            'category[]': {
                validators: {
                    choice: {
                        min: 1,
                        message: 'Please choose atleast one category'
                    }
                }
            },
            image: {
                validators: {
                    notEmpty: {
                        message: image_required
                    },
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        maxSize: 2048 * 1024,
                        message: image_message
                    }
                }
            },
            timezone: {
                validators: {
                    notEmpty: {
                        message: timezone
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // City Form
    $('#update-city-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            country: {
                validators: {
                    notEmpty: {
                        message: country_d_name
                    }
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: city_name
                    },
                    stringLength: {
                        max: 30,
                        message: city_strlen
                    },
                    regexp: {
                        regexp: /^[a-z\s]+$/i,
                        message: 'City consist of alphabetical characters and spaces only'
                    }
                }
            },
            'category[]': {
                validators: {
                    choice: {
                        min: 1,
                        message: 'Please choose atleast one category'
                    }
                }
            },
            image: {
                validators: {
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        maxSize: 2048 * 1024,
                        message: image_message
                    }
                }
            },
            timezone: {
                validators: {
                    notEmpty: {
                        message: timezone
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // Add Category
    $('#add-category-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            image: {
                validators: {
                    notEmpty: {
                        message: icon_required
                    },
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        maxSize: 256 * 256,
                        message: icon_size
                    }
                }
            },
            category: {
                validators: {
                    notEmpty: {
                        message: category_required
                    },
                    stringLength: {
                        max: 50,
                        message: category_strlen
                    },
                    remote: {
                        data: {"_token": $("input[name=_token]").val(), "action": $("#actionname").val(), 'category_id': $("#category_id").val()},
                        url: categoryExists,
                        type: 'POST',
                        message: category_exists
                    }
                }
            }

        }
    }).on('click', '.addButton', function () {
        var $template = $('#optionTemplate'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $option = $clone.find('[name="subcategory[]"]');

        // Add new field
//        $('#add-category-form').bootstrapValidator('addField', $option);
    }).on('click', '.removeButton', function () {
        var $row = $(this).parents('.form-group'),
                $option = $row.find('[name="subcategory[]"]');

        // Remove element containing the option
        $row.remove();

        // Remove field
//        $('#add-category-form').bootstrapValidator('removeField', $option);
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // Update Country
    $('#update-category-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            category: {
                validators: {
                    notEmpty: {
                        message: category_required
                    },
                    stringLength: {
                        max: 50,
                        message: category_strlen
                    },
                    remote: {
                        data: function (validator, $field, value) {
                            return {
                                category_id: validator.getFieldElements('category_id').val(),
                                action: "edit",
                                _token: $("input[name=_token]").val()
                            };
                        },
                        url: categoryExists,
                        type: 'POST',
                        message: category_exists
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // Add Subcategory
    $('#add-subcategory-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            category: {
                validators: {
                    notEmpty: {
                        message: category_sel_required
                    }
                }
            },
            'subcategory[]': {
                validators: {
                    notEmpty: {
                        message: subcategory_required
                    },
                    stringLength: {
                        max: 50,
                        message: subcategory_strlen
                    },
                    remote: {
                        data: {"_token": $("input[name=_token]").val(), "action": $("#actionname").val(), 'subcategory_id': $("#subcategory_id").val()},
                        url: subCategoryExists,
                        type: 'POST',
                        message: subcategory_exists
                    }
                }
            }

        }
    }).on('click', '.addButton', function () {
        var $template = $('#optionTemplate2'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $option = $clone.find('[name="subcategory[]"]');

        // Add new field
        $('#add-subcategory-form').bootstrapValidator('addField', $option);
    }).on('click', '.removeButton', function () {
        var $row = $(this).parents('.form-group'),
                $option = $row.find('[name="subcategory[]"]');

        // Remove element containing the option
        $row.remove();

        // Remove field
        $('#add-subcategory-form').bootstrapValidator('removeField', $option);
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // Update Country
    $('#update-subcategory-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            subcategory: {
                validators: {
                    notEmpty: {
                        message: subcategory_required
                    },
                    stringLength: {
                        max: 50,
                        message: subcategory_strlen
                    },
                    remote: {
                        data: function (validator, $field, value) {
                            return {
                                subcategory_id: validator.getFieldElements('subcategory_id').val(),
                                action: "edit",
                                _token: $("input[name=_token]").val()
                            };
                        },
                        url: subCategoryExists,
                        type: 'POST',
                        message: subcategory_exists
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    //Add Policy Form
    $('#add-policy-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            image: {
                validators: {
                    notEmpty: {
                        message: icon_required
                    },
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        maxSize: 256 * 256,
                        message: icon_size
                    }
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: policy_required
                    },
                    stringLength: {
                        max: 50,
                        message: policy_len
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    //Update Policy Form
    $('#update-policy-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            image: {
                validators: {
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        maxSize: 256 * 256,
                        message: icon_size
                    }
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: policy_required
                    },
                    stringLength: {
                        max: 50,
                        message: policy_len
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    //Add Activity Form
    $('#activity-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: title_required
                    },
                    stringLength: {
                        max: 100,
                        message: title_strlen
                    }
                }
            },
            category: {
                validators: {
                    notEmpty: {
                        message: category_sel_required
                    }
                }
            },
            // subcategory: {
            //     validators: {
            //         notEmpty: {
            //             message: subcategory_sel_required
            //         }
            //     }
            // },
            city: {
                validators: {
                    notEmpty: {
                        message: city_sel_required
                    }
                }
            },
            merchant_id: {
                validators: {
                    notEmpty: {
                        message: "Please select publisher"
                    }
                }
            },
            image: {
                validators: {
                    notEmpty: {
                        message: image_required
                    },
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        maxSize: 2048 * 1024,
                        message: image_message
                    }
                }
            },
            actual_price: {
                validators: {
                    notEmpty: {
                        message: actual_price_required
                    },
                    numeric: {
                        message: 'Please enter valid number',
                    }
                }
            },
            display_price: {
                validators: {
                    numeric: {
                        message: 'Please enter valid number',
                    }
                }
            },
            'package_title[]': {
                validators: {
                    notEmpty: {
                        message: package_title_required
                    }
                }
            },
            'package_actual_price[]': {
                validators: {
                    notEmpty: {
                        message: package_price
                    }
                }
            }
        }
    }).on('click', '.add-more-package', function () {
        var $template = $('#package_option_clone'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $packageTitle = $clone.find('[name="package_title[]"]'),
                $packagePrice = $clone.find('[name="package_actual_price[]"]'),
                $packageDescription = $clone.find('[name="package_description[]"]');

        // Package Validity
        $clone.find('[name="package_validity[]"]').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "yyyy-mm-dd",
            startDate: '-0d'
        });

        // Add new field
        $('#activity-form').bootstrapValidator('addField', $packageTitle);
        $('#activity-form').bootstrapValidator('addField', $packagePrice);
        // $packageDescription.summernote();
    }).on('click', '.remove-package', function () {
        var $row = $(this).closest('.row'),
                $packageTitle = $row.find('[name="package_title[]"]'),
                $packagePrice = $row.find('[name="package_actual_price[]"]');

        $('#activity-form').bootstrapValidator('removeField', $packageTitle);
        $('#activity-form').bootstrapValidator('removeField', $packagePrice);
        $row.remove();
    }).on('click', '.addFaq', function () {
        var $template = $('#faq-template'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template);
    }).on('click', '.removeFaq', function () {
        var $row = $(this).parents('.form-group');
        $row.remove();
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    // Activity Form
    $('#update-activity-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled'],
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: title_required
                    },
                    stringLength: {
                        max: 100,
                        message: title_strlen
                    }
                }
            },
            category: {
                validators: {
                    notEmpty: {
                        message: category_sel_required
                    }
                }
            },
            // subcategory: {
            //     validators: {
            //         notEmpty: {
            //             message: subcategory_sel_required
            //         }
            //     }
            // },
            city: {
                validators: {
                    notEmpty: {
                        message: city_sel_required
                    }
                }
            },
            image: {
                validators: {
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        maxSize: 2048 * 1024,
                        message: image_message
                    }
                }
            },
            actual_price: {
                validators: {
                    notEmpty: {
                        message: actual_price_required
                    },
                    numeric: {
                        message: 'Please enter valid number',
                    }
                }
            },
            display_price: {
                validators: {
                    numeric: {
                        message: 'Please enter valid number',
                    }
                }
            }
        }
    }).on('click', '.add-more-package', function () {
        var $template = $('#package_option_clone'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $packageTitle = $clone.find('[name="package_title[]"]'),
                $packagePrice = $clone.find('[name="package_actual_price[]"]'),
                $packageDescription = $clone.find('[name="package_description[]"]');
        // Package Validity
        $clone.find('[name="package_validity[]"]').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "yyyy-mm-dd",
            startDate: '-0d'
        });

        // Add new field
        $('#activity-form').bootstrapValidator('addField', $packageTitle);
        $('#activity-form').bootstrapValidator('addField', $packagePrice);
        // $packageDescription.summernote();
    }).on('click', '.remove-package', function () {
        var $row = $(this).closest('.row'),
                $packageTitle = $row.find('[name="package_title[]"]'),
                $packagePrice = $row.find('[name="package_actual_price[]"]');
        $('#activity-form').bootstrapValidator('removeField', $packageTitle);
        $('#activity-form').bootstrapValidator('removeField', $packagePrice);
        if ($(this).attr('data-id') != "empty") {
            removePackage($(this).attr('data-id'), $row);
        } else {
            $row.remove();
        }
    }).on('click', '.addFaq', function () {
        var $template = $('#faq-template'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template);
    }).on('click', '.removeFaq', function () {
        var $row = $(this).parents('.form-group');
        $row.remove();
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    //Add Policy Form
    $('#add-activity-policy-form').bootstrapValidator({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            imagefile: {
                validators: {
                    notEmpty: {
                        message: icon_required
                    },
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png,image/jpg',
                        maxSize: 256 * 256,
                        message: icon_size
                    }
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: policy_required
                    },
                    stringLength: {
                        max: 50,
                        message: policy_len
                    },
                    remote: {
                        data: function (validator, $field, value) {
                            return {
                                policy_id: validator.getFieldElements('policy_id').val(),
                                action: "add",
                                _token: $("input[name=_token]").val()
                            };
                        },
                        url: checkPolicyExists,
                        type: 'POST',
                        message: policy_exists
                    }
                }
            }
        }
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    }).on('success.form.bv', function (e) {
        // Prevent form submission
        e.preventDefault();
        // Get the form instance
        var $form = $(e.target);
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#activity-policy-modal").modal('hide');
                $("#terms-and-policy").append(data);
            }
        });
    });
    /*--------------*/


    $('#pacakge-config').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            'package_title': {
                selector: '.package_title',
                validators: {
                    notEmpty: {
                        message: package_title_required
                    }
                }
            },
            'package_actual_price': {
                selector: '.package_actual_price',
                validators: {
                    notEmpty: {
                        message: 'Please enter actual price'
                    },
                    numeric: {
                        message: 'Please enter numeric value'
                    },
                    stringLength: {
                        max: 10,
                        message: "Plaese enter maximum  10 digits"
                    },
                }
            },
            'package_display_price': {
                selector: '.package_display_price',
                validators: {
                    numeric: {
                        message: 'Please enter numeric value'
                    },
                    stringLength: {
                        max: 10,
                        message: "Plaese enter maximum  10 digits"
                    },
                }
            },
            'booking_display_price': {
                selector: '.booking_display_price',
                validators: {
                    numeric: {
                        message: 'Please enter numeric value'
                    },
                    stringLength: {
                        max: 10,
                        message: "Plaese enter maximum  10 digits"
                    }
                }
            },
            'booking_title': {
                selector: '.booking_title',
                validators: {
                    notEmpty: {
                        message: booking_title_required
                    }
                }
            },
            'minimum_quantity': {
                selector: '.minimum_quantity',
                validators: {
                    notEmpty: {
                        message: "Please enter minimum quantity"
                    },
                    numeric: {
                        message: 'Please enter numeric value'
                    },
                    stringLength: {
                        max: 10,
                        message: "Plaese enter maximum  10 digits"
                    }
                }
            },
            'booking_actual_price': {
                selector: '.booking_actual_price',
                validators: {
                    notEmpty: {
                        message: 'Please enter actual price'
                    },
                    numeric: {
                        message: 'Please enter numeric value'
                    }, stringLength: {
                        max: 10,
                        message: "Plaese enter maximum  10 digits"
                    }
                }
            },
            'maximum_quantity': {
                selector: '.maximum_quantity',
                validators: {
                    numeric: {
                        message: 'Please enter numeric value'
                    },
                    stringLength: {
                        max: 10,
                        message: "Plaese enter maximum  10 digits"
                    }
                }
            },
        }
    }).on('click', '.add-more-package', function () {


        var $template = $('#package_option_clone'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $packageTitle = $clone.find('.package_title'),
                $packagePrice = $clone.find('.package_actual_price'),
                $packagedisplayPrice = $clone.find('.package_display_price'),
                $packageDescription = $clone.find('.package_description_clone');
        $quntybooking_title = $clone.find('.booking_title'),
                $quntybooking_actual_price = $clone.find('.booking_actual_price');
        $quntybooking_display_price = $clone.find('.booking_display_price');
        $quntyminimum_quantity = $clone.find('.minimum_quantity');

        // Add new field

        $('#pacakge-config').bootstrapValidator('addField', $packageTitle);
        $('#pacakge-config').bootstrapValidator('addField', $packagePrice);
        $('#pacakge-config').bootstrapValidator('addField', $packagedisplayPrice);
        $('#pacakge-config').bootstrapValidator('addField', $quntybooking_title);
        $('#pacakge-config').bootstrapValidator('addField', $quntybooking_actual_price);
        $('#pacakge-config').bootstrapValidator('addField', $quntybooking_display_price);
        $('#pacakge-config').bootstrapValidator('addField', $quntyminimum_quantity);

        $packageDescription.summernote({
            callbacks: {
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    // Firefox fix
                    setTimeout(function () {
                        document.execCommand('insertText', false, bufferText);
                    }, 10);
                }
            }
        });
        genrateInputName($clone);
        $('.package_validity').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "yyyy-mm-dd",
            startDate: '-0d'
        });
    }).on('click', '.remove-package', function () {
        var $row = $(this).closest('.row'),
                $bookingTitle = $row.find('.booking_title'),
                $bookingPrice = $row.find('.booking_actual_price');
        $bookingquantity = $row.find('.minimum_quantity');
        $bookingdisplayPrice = $row.find('.package_display_price');
        $bookinmaxgquantity = $row.find('.maximum_quantity');
        $packagedisplayPrice = $row.find('.package_display_price');
        $quntybooking_title = $row.find('.booking_title'),
                $quntybooking_actual_price = $row.find('.booking_actual_price');
        $quntyminimum_quantity = $row.find('.minimum_quantity');
        $quntybooking_display_price = $row.find('.booking_display_price');
        // $row.remove();
        if ($(this).attr('data-id') != "empty") {
            removePackage($(this).attr('data-id'), $row);
        } else {
            $row.remove();
        }
        $('#pacakge-config').bootstrapValidator('removeField', $bookingTitle);
        $('#pacakge-config').bootstrapValidator('removeField', $bookingPrice);
        $('#pacakge-config').bootstrapValidator('removeField', $bookingquantity);
        $('#pacakge-config').bootstrapValidator('removeField', $bookingdisplayPrice);
        $('#pacakge-config').bootstrapValidator('removeField', $bookinmaxgquantity);
        $('#pacakge-config').bootstrapValidator('removeField', $packagedisplayPrice);

        $('#pacakge-config').bootstrapValidator('removeField', $quntybooking_title);
        $('#pacakge-config').bootstrapValidator('removeField', $quntybooking_actual_price);
        $('#pacakge-config').bootstrapValidator('removeField', $quntyminimum_quantity);
        $('#pacakge-config').bootstrapValidator('removeField', $quntybooking_display_price);

    }).on('click', '.add-booking', function () {

        var $template = $(this).closest('.package-row').find('#booking_detail_clone'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $bookingTitle = $(this).closest('.package-row').find('.booking_title'),
                $bookingPrice = $(this).closest('.package-row').find('.booking_actual_price');
        $bookingdisplayPrice = $(this).closest('.package-row').find('.booking_display_price');
        $bookingminiqty = $(this).closest('.package-row').find('.minimum_quantity');
        $bookingmaxqty = $(this).closest('.package-row').find('.maximum_quantity');
        // Add new field
        $('#pacakge-config').bootstrapValidator('addField', $bookingTitle);
        $('#pacakge-config').bootstrapValidator('addField', $bookingPrice);
        $('#pacakge-config').bootstrapValidator('addField', $bookingdisplayPrice);
        $('#pacakge-config').bootstrapValidator('addField', $bookingminiqty);
        $('#pacakge-config').bootstrapValidator('addField', $bookingminiqty);
        $('#pacakge-config').bootstrapValidator('addField', $bookingmaxqty);
    }).on('click', '.remove-booking', function () {
        var $row = $(this).closest('.booking-details'),
                $packageTitle = $row.find('.booking_title'),
                $packagePrice = $row.find('.booking_actual_price');
        $bookingdisplayPrice = $row.find('.booking_display_price');
        $packageminiqty = $row.find('.minimum_quantity');
        $bookingmaxqty = $row.find('.maximum_quantity');

        // Remove element containing the option

        // $row.remove();
        if ($(this).attr('data-id') != "empty") {
            removePackageQuantity($(this).attr('data-id'), $row);
        } else {
            $row.remove();
        }

        // Remove field
        $('#pacakge-config').bootstrapValidator('removeField', $packageTitle);
        $('#pacakge-config').bootstrapValidator('removeField', $packagePrice);
        $('#pacakge-config').bootstrapValidator('removeField', $bookingdisplayPrice);
        $('#pacakge-config').bootstrapValidator('removeField', $packageminiqty);
        $('#pacakge-config').bootstrapValidator('removeField', $bookingmaxqty);
    }).on('click', '.addFaq', function () {
        var $template = $('#faq-template'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template);
    }).on('click', '.removeFaq', function () {
        var $row = $(this).parents('.form-group');
        $row.remove();
    }).on('error.validator.bv', function (e, data) {
        data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
    });
    /*-----------Activity reson decline ---------*/
    $('#decline-activity').bootstrapValidator({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            'reasondecline': {
                validators: {
                    notEmpty: {
                        message: "Please enter reason For decline"
                    }
                }
            }
        }
    })

    /*----------random convert----*/

    function genrateInputName($clone) {
        var randomNumber = randomNumberFromRange(101, 301);
        $clone.find('.package_title').attr('name', "data[" + randomNumber + "][package_title]");
        $clone.find('.package_actual_price').attr('name', "data[" + randomNumber + "][package_actual_price]")
        $clone.find('.package_display_price').attr('name', "data[" + randomNumber + "][package_display_price]")
        $clone.find('.package_validity').attr('name', "data[" + randomNumber + "][package_validity]")
        $clone.find('.package_description_clone').attr('name', "data[" + randomNumber + "][package_description]")
        $clone.find('.booking_title').attr('name', "data[" + randomNumber + "][child][booking_title][]")
        $clone.find('.booking_actual_price').attr('name', "data[" + randomNumber + "][child][booking_actual_price][]")
        $clone.find('.booking_display_price').attr('name', "data[" + randomNumber + "][child][booking_display_price][]")
        $clone.find('.minimum_quantity').attr('name', "data[" + randomNumber + "][child][minimum_quantity][]")
        $clone.find('.maximum_quantity').attr('name', "data[" + randomNumber + "][child][maximum_quantity][]")

    }
    function randomNumberFromRange(min, max)
    {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    /*-----------------*/
    // Flashout message
    $(document).find(".alert-dismissable").delay(5000).fadeOut('slow');
    $('.chosen-select').chosen({width: "100%"});
    // Reset change password Form
    $('#changepassword-modal').on('hidden.bs.modal', function () {
        $('#changepassword-form').bootstrapValidator('resetForm', true);
    });
    $('#add-continent').on('shown.bs.modal', function () {
        $('#add-countitnent-form').bootstrapValidator('resetForm', true);
    });
    $('#add-continent').on('hidden.bs.modal', function () {
        $('#add-countitnent-form').bootstrapValidator('resetForm', true);
    });

    $('#update-continent').on('shown.bs.modal', function () {
        $('#update-countitnent-form').bootstrapValidator('resetForm', true);
    });
    $('#update-continent').on('hidden.bs.modal', function () {
        $('#update-countitnent-form').bootstrapValidator('resetForm', true);
    });

    $('#add-country-modal').on('shown.bs.modal', function () {
        $('#add-country-form').bootstrapValidator('resetForm', true);
    });
    $('#add-country-modal').on('hidden.bs.modal', function () {
        $('#add-country-form').bootstrapValidator('resetForm', true);
    });

    $('#add-category').on('shown.bs.modal', function () {
        $('#add-category-form').bootstrapValidator('resetForm', true);
    });
    $('#add-category').on('hidden.bs.modal', function () {
        $('#add-category-form').bootstrapValidator('resetForm', true);
    });
    $('#update-category').on('shown.bs.modal', function () {
        $('#update-category-form').bootstrapValidator('resetForm', true);
    });
    $('#update-category').on('hidden.bs.modal', function () {
        $('#update-category-form').bootstrapValidator('resetForm', true);
    });

    $('#add-subcategory').on('shown.bs.modal', function () {
        $('#add-subcategory-form').bootstrapValidator('resetForm', true);
    });
    $('#add-subcategory').on('hidden.bs.modal', function () {
        $('#add-subcategory-form').bootstrapValidator('resetForm', true);
    });

    $('#update-subcategory').on('shown.bs.modal', function () {
        $('#update-subcategory-form').bootstrapValidator('resetForm', true);
    });
    $('#update-subcategory').on('hidden.bs.modal', function () {
        $('#update-subcategory-form').bootstrapValidator('resetForm', true);
    });
    $('#policy-modal').on('hidden.bs.modal', function () {
        $('#add-policy-form').bootstrapValidator('resetForm', true);
    });
    $('#update-policy-modal').on('hidden.bs.modal', function () {
        $('#update-policy-form').bootstrapValidator('resetForm', true);
    });
    $('#activity-policy-modal').on('hidden.bs.modal', function () {
        $('#add-activity-policy-form').bootstrapValidator('resetForm', true);
    });
    // Package Validity
    $('.package_validity').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: "yyyy-mm-dd",
        startDate: '-0d'
    });
    $('.package_time').datetimepicker({
        format: 'LT'
    });
    $('#booking-from-date').datepicker({
        todayHighlight: true,
        format: "yyyy-mm-dd",
        autoclose: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }).on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#booking-to-date').datepicker('setStartDate', startDate);
    });
    $('#booking-to-date').datepicker({
        todayHighlight: true,
        format: "yyyy-mm-dd",
        autoclose: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }).on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#booking-from-date').datepicker('setEndDate', endDate);
    });
    $('#parti_from_validity').datepicker({
        todayHighlight: true,
        format: "yyyy-mm-dd",
        autoclose: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }).on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#parti_to_validity').datepicker('setStartDate', startDate);
        $('#parti_to_validity').removeAttr('disabled');
    });
    $('#parti_to_validity').datepicker({
        todayHighlight: true,
        format: "yyyy-mm-dd",
        autoclose: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }).on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#parti_from_validity').datepicker('setEndDate', endDate);
    });
    // $('.parti_from_validity').datepicker({
    //         // todayBtn: "linked",
    //         keyboardNavigation: false,
    //         forceParse: false,
    //         autoclose: true,
    //         format: "yyyy-mm-dd",
    //         // startDate: '-0d'
    // });
    // $('.parti_to_validity').datepicker({
    //     // todayBtn: "linked",
    //     keyboardNavigation: false,
    //     forceParse: false,
    //     autoclose: true,
    //     format: "yyyy-mm-dd",
    //     // startDate: '-0d'
    // });
});
function checkDuplicates() {
    // get all input elements
    var $elems = $('.continent-name');

    // we store the inputs value inside this array
    var values = [];
    // return this
    var isDuplicated = false;
    // loop through elements
    $elems.each(function () {
        //If value is empty then move to the next iteration.
        if (!this.value)
            return true;
        //If the stored array has this value, break from the each method
        if (values.indexOf(this.value) !== -1) {
            isDuplicated = true;
            return false;
        }
        // store the value
        values.push(this.value);
    });
    return isDuplicated;
}
// Delete single record [ this can use for all single deltetion ]
$(".delete-row").click(function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    var dataid = $(this).attr('data-id');
    return bootbox.confirm("Are you sure to delete this " + dataid + "?", function (result) {
        if (result) {
            window.location = href;
        }
    });
});

// not delete loacation and category 

$(".non-deleteable").click(function (e) {
    e.preventDefault();
    var dataid = $(this).attr('data-id');
    return bootbox.alert("This " + dataid + " belongs to some activites so you are not allowed to delete.");
});

$(".select2_demo_3").select2({
    placeholder: "Select a merchant",
    allowClear: true
});

///package options add 
$(".add-package-check").click(function (e) {
    e.preventDefault();
    var dataid = $(this).attr('data-id');
    return bootbox.alert("Please add package option after that publish this activity.");
});

$(".ad-activity").click(function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    var dataid = $(this).attr('data-id');
    var datvid = $(this).attr('data-vid');
    return bootbox.confirm("Are you sure to " + dataid + " this activity ?", function (result) {
        if (result) {
            if (dataid == "decline") {
                $("#decline-activity-modal").modal('show');
                $("#decline-activity").attr('action', href);
                console.log(href);
            } else {
                window.location = href;
            }
        }
    });
});

$(".order-statusac").click(function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    var dataid = $(this).attr('data-id');
    var datvid = $(this).attr('data-vid');
    return bootbox.confirm("Are you sure to " + dataid + " this order ?", function (result) {
        if (result) {

            window.location = href;

        }
    });
});

$('.i-check-show').hide();
var checkAll = $('.i-check-show');
var checkboxes = $('.i-event-show');
checkboxes.on('ifChanged', function (event) {
    if (checkboxes.filter(':checked').length == "0") {
        checkAll.hide(100);
    } else {
        checkAll.show(100);
    }
    checkAll.iCheck('update');
});


$(function () {
    var checkAll_1 = $('#example-select-all');
    var checkboxes_1 = $('.checkall');



    checkAll_1.on('ifChecked ifUnchecked', function (event) {
        if (event.type == 'ifChecked') {
            checkboxes_1.iCheck('check');
        } else {
            checkboxes_1.iCheck('uncheck');
        }
    });

    checkboxes_1.on('ifChanged', function (event) {
        if (checkboxes_1.filter(':checked').length == checkboxes_1.length) {
            checkAll_1.prop('checked', 'checked');
        } else {
            checkAll_1.prop('checked', '');
        }
        checkAll_1.iCheck('update');
    });
});
// For User Management
$('#example-select-all').on('ifChecked', function (event) {
    $('.checkall').iCheck('check');
    triggeredByChild = false;
});

$('#example-select-all').on('ifUnchecked', function (event) {
    if (!triggeredByChild) {
        $('.checkall').iCheck('uncheck');
    }
    triggeredByChild = false;
});
// Multiple user delete
$('#delete-selected-user').on('click', function (e) {
    var allVals = [];
    $("#sub_chk:checked").each(function () {
        allVals.push($(this).attr('data-id'));
    });
    if (allVals.length <= 0) {
        e.preventDefault();
        return bootbox.alert('Please select atleast one system user');
    } else {
        var message = "Are you sure want to delete this system user(s)?";
        e.preventDefault();
        href = $(this).attr('href');
        return bootbox.confirm(message, function (check) {
            if (check) {
                var join_selected_values = allVals.join(",");
                $.ajax({
                    type: "POST",
                    url: multipleUserDelete,
                    cache: false,
                    data: {
                        "_token": $("input[name=_token]").val(),
                        "ids": join_selected_values,
                    },
                    success: function (data) {
                        location.reload();
                    }
                });
            }
        });
    }
});
$(".status-change").on('click', function (e) {
    var id = $(this).attr('data-id');
    var data_type = $(this).attr('data-type');
    if ($(this).text() == "Active") {
        var status = "Inactive";
    } else {
        var status = "Active";
    }
    if(data_type == "system user" || data_type == "customer"){
        var url = changeUserStatus;
    }else if(data_type == "merchant"){
        var url = changeMerchantStatus;
    }else{
        var url = changeActivityStatusUrl;
    }
    $this = $(this);
    return bootbox.confirm("Are you sure to " + status + " this "+data_type+"?", function (check) {
        if (check) {
            changeStatus(url,id,status,data_type);
            if ($this.text() == "Active") {
                $this.removeClass("label-primary");
                $this.text("Inactive");
            }else{
                $this.addClass("label-primary");
                $this.text("Active");
            }
        }
    });
});
function changeStatus(url,id,status,data_type) {
    $.ajax({
        type: "PUT",
        url: url,
        cache: false,
        data: {
            "_token": $("input[name=_token]").val(),
            "id": id,
            "status": status,
            "data_type": data_type
        },
        success: function (data) {
            $(".status-message").text(data.status);
            $(".status-flash-div").css('display', 'block');
            $(".status-flash-div").delay(5000).fadeOut('slow');
            $(".status-flash-div").removeClass('hide');
        }
    });
}
$(document).on('click', '#reset-search-product', function () {
    location.reload();
});

// Multiple Activity delete
$('#delete-selected-activity').on('click', function (e) {
    var allVals = [];
    $("#sub_chk:checked").each(function () {
        allVals.push($(this).attr('data-id'));
    });
    if (allVals.length <= 0) {
        e.preventDefault();
        return bootbox.alert('Please select atleast one activity');
    } else {
        var message = "Are you sure want to delete this activities?";
        e.preventDefault();
        href = $(this).attr('href');
        return bootbox.confirm(message, function (check) {
            if (check) {
                var join_selected_values = allVals.join(",");
                $.ajax({
                    type: "POST",
                    url: multipleActivityDelete,
                    cache: false,
                    data: {
                        "_token": $("input[name=_token]").val(),
                        "ids": join_selected_values,
                    },
                    success: function (data) {
                        location.reload();
                    }
                });
            }
        });
    }
});


// Multiple Activity Status change
$('.status-selected-activity').on('click', function (e) {
    var status = $(this).attr('data-status');
    var allVals = [];
    $("#sub_chk:checked").each(function () {
        allVals.push($(this).attr('data-id'));
    });
    if (allVals.length <= 0) {
        e.preventDefault();
        return bootbox.alert('Please select atleast one activity');
    } else {
        var message = "Are you sure want to " + status + " this activities?";
        e.preventDefault();
        href = $(this).attr('href');

        return bootbox.confirm(message, function (check) {
            if (check) {
                var join_selected_values = allVals.join(",");
                $.ajax({
                    type: "POST",
                    url: multipleActivityStatus,
                    cache: false,
                    data: {
                        "_token": $("input[name=_token]").val(),
                        "ids": join_selected_values,
                        "status": status,
                    },
                    success: function (data) {
                        location.reload();
                    }
                });
            }
        });
    }
});

// Edit Continent
$(".edit-continent").click(function () {
    var id = $(this).attr('data-id');
    var continent = $(this).attr('data-continent');
    $("#update-continent").modal('show');
    $("#update-continent").find('#update_continent_id').val(id);
    $("#update-continent").find('#continent').val(continent);
});
// Add Country
$(".add-country").click(function () {
    var id = $(this).attr('data-id');
    var continent = $(this).attr('data-continent');
    $("#add-country-modal").modal('show');
    $("#add-country-modal").find('#country_continent_id').val(id);
    $("#add-country-modal").find('.add-country-title').text("Add Country In " + continent);
});
// Edit Country
$(".edit-country").click(function () {
    var continent_id = $(this).attr('data-continentid');
    var continent = $(this).attr('data-continent');
    var country_id = $(this).attr('data-countryid');
    var country = $(this).attr('data-countryname');
    var status = $(this).attr('data-status');
    $("#update-country-modal").modal('show');
    $("#update-country-modal").find('#country_continent_id').val(continent_id);
    $("#update-country-modal").find('#country_id').val(country_id);
    $("#update-country-modal").find('.update-country-title').text("Update Country In " + continent);
    $("#update-country-modal").find('#country-name').val(country);
    $("#update-country-modal").find('#status').val(status);
});
// Edit Category
$(".edit-category").click(function () {
    var id = $(this).attr('data-id');
    var category = $(this).attr('data-category');
    var image = $(this).attr('data-image');
    $("#update-category").modal('show');
    $("#update-category").find('#category_id').val(id);
    $("#update-category").find('#category').val(category);
    $("#update-category").find('#category-image').attr('src', image);
});
// Edit Subcategory
$(".edit-subcategory").click(function () {
    var id = $(this).attr('data-id');
    var subcategory = $(this).attr('data-subcategory');
    $("#update-subcategory").modal('show');
    $("#update-subcategory").find('#subcategory_id').val(id);
    $("#update-subcategory").find('#subcategory').val(subcategory);
});
// Edit Policy
$(".edit-policy").click(function () {
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-name');
    var image = $(this).attr('data-image');
    $("#update-policy-modal").modal('show');
    $("#update-policy-modal").find('#policy_id').val(id);
    $("#update-policy-modal").find('#policy-name').val(name);
    $("#update-policy-modal").find('#policy-image').attr('src', image);
});

/*
 * Actvity Script Start
 * 
 */
$('#is_package_options').on('ifChecked', function (event) {
    $("#package_options_content").css('display', 'block');
});

$('#is_package_options').on('ifUnchecked', function (event) {
    $("#package_options_content").css('display', 'none');
});

$('#is_what_to_expect').on('ifChecked', function (event) {
    $("#what_to_expect_content").css('display', 'block');
});

$('#is_what_to_expect').on('ifUnchecked', function (event) {
    $("#what_to_expect_content").css('display', 'none');
});

$('#is_activity_information').on('ifChecked', function (event) {
    $("#activity_information_content").css('display', 'block');
});

$('#is_activity_information').on('ifUnchecked', function (event) {
    $("#activity_information_content").css('display', 'none');
});

$('#is_how_to_use').on('ifChecked', function (event) {
    $("#how_to_use_content").css('display', 'block');
});

$('#is_how_to_use').on('ifUnchecked', function (event) {
    $("#how_to_use_content").css('display', 'none');
});

$('#is_cancellation_policy').on('ifChecked', function (event) {
    $("#cancellation_policy_content").css('display', 'block');
});

$('#is_cancellation_policy').on('ifUnchecked', function (event) {
    $("#cancellation_policy_content").css('display', 'none');
});

// $('.merchant-select').on('ifChecked', function (event) {
//     $("#activty-merchant").css('display', 'block');
// });
// $("#activty-merchant").css('display', 'none');
// $(".merchant-active").css('display', 'block');
// $('.merchant-select').on('ifUnchecked', function (event) {
//     $("#activty-merchant").css('display', 'none');
// });


$("#activty-city").change(function () {
    $.ajax({
        type: "POST",
        url: getCategories,
        cache: false,
        data: {
            "_token": $("input[name=_token]").val(),
            "id": $(this).val(),
        },
        success: function (data) {
            $('#activty-category').empty();
            $('#activty-subcategory').empty();
            $('#activty-category').append($("<option value=''></option>").text('--Select Category--'));
            $('#activty-subcategory').append($("<option value=''></option>").text('--Select Subcategory--'));
            $.each(data.categories, function (key, value) {
                $('#activty-category')
                        .append($("<option></option>")
                                .attr("value", key)
                                .text(value));
            });
            $("#activity-form").bootstrapValidator("revalidateField", 'category');
            $("#update-activity-form").bootstrapValidator("revalidateField", 'category');
        }
    });
});

$("#activty-category").change(function () {
    $.ajax({
        type: "POST",
        url: getSubcategories,
        cache: false,
        data: {
            "_token": $("input[name=_token]").val(),
            "id": $(this).val(),
        },
        success: function (data) {
            $('#activty-subcategory').empty();
            $('#activty-subcategory').append($("<option value=''></option>").text('--Select Subcategory--'));
            $.each(data.subcategories, function (key, value) {
                $('#activty-subcategory')
                        .append($("<option></option>")
                                .attr("value", key)
                                .text(value));
            });
            // $("#activity-form").bootstrapValidator("revalidateField", 'subcategory');
            // $("#update-activity-form").bootstrapValidator("revalidateField", 'subcategory');
        }
    });
});
function removePackage(id, row) {
    var message = "Are you sure want to delete this package?";
    return bootbox.confirm(message, function (check) {
        if (check) {
            $.ajax({
                type: "POST",
                url: removePacakageUrl,
                cache: false,
                data: {
                    "_token": $("input[name=_token]").val(),
                    "id": id,
                },
                success: function (data) {
                    if (data.code == 200) {
                        row.remove();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
}
function removePackageQuantity(id, row) {
    var message = "Are you sure want to delete this package quantity?";
    return bootbox.confirm(message, function (check) {
        if (check) {
            $.ajax({
                type: "POST",
                url: removePacakageQuantityUrl,
                cache: false,
                data: {
                    "_token": $("input[name=_token]").val(),
                    "id": id,
                },
                success: function (data) {
                    if (data.code == 200) {
                        row.remove();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
}
$(function () {
    // Multiple images preview in browser
    var imagesPreview = function (input, placeToInsertImagePreview, ids) {
        var _URL = window.URL || window.webkitURL;
        var ids = JSON.parse(ids);
        if (input.files) {
            var filesAmount = input.files.length;
            if (filesAmount > 0) {
                for (i = 0; i < filesAmount; i++) {
                    $id = ids[i];
                    input.files[i].id = $id;
                    img = new Image();
                    img.src = _URL.createObjectURL(input.files[i]);
                    var html = "<div class='preview-div'><img src='" + img.src + "' class='preview-image'><a href='javascript:void(0)' data-id='" + ids[i] + "' class='image-delete' title='Remove Image'><i class='fa fa-trash-o'></i></a></div>";
                    $(".gallery").append(html);
                }
            }
        }

    };


    $('#explore-images').on('change', function () {
        $this = this;
        var form_data = new FormData();
        var ins = document.getElementById('explore-images').files.length;
        if (this.files[0].size / 1024 < 100) {
            bootbox.alert("Please upload file greter than 100kb. Thanks!!");
            return false;
        }
        for (var x = 0; x < ins; x++) {
            form_data.append("files[]", document.getElementById('explore-images').files[x]);
        }
        $.ajax({
            type: 'POST',
            url: uploadExploreImages, // point to server-side PHP script 
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                toastr.success("Image has been saved successfully");
                imagesPreview($this, 'div.gallery', response);
            }
        });

    });
});
$(document).on("click", ".image-delete", function () {
    var id = $(this).attr('data-id');
    console.log(id);
    var message = "Are you sure want to remove this image?";
    var div = $(this).closest('.preview-div');
    if (id != "empty") {
        return bootbox.confirm(message, function (check) {
            if (check) {
                $.ajax({
                    type: "POST",
                    url: removeExploreImage,
                    cache: false,
                    data: {
                        "_token": $("input[name=_token]").val(),
                        "id": id,
                    },
                    success: function (data) {
                        toastr.success("Image has been removed");
                        div.remove();
                    }
                });
            }
        });
    } else {
        div.remove();
    }
});
$(function () {
    $('.img-pop').on('click', function () {

        $('.imagepreview').attr('src', $(this).attr('src'));
        $('#imagemodal').modal('show');
    });
});
$(".create-merchant-password").click(function () {
    $("#create-password-modal").modal('show');
    var merchant_id = $(this).attr('data-id');
    $("#merchant-id").val(merchant_id);
});
// Reset change password Form
$('#create-password-modal').on('hidden.bs.modal', function () {
    $('#resetpassword-form').bootstrapValidator('resetForm', true);
});
// Confirm Booking
$(document).on("click", ".confirm-booking", function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    return bootbox.confirm("Are you sure to confirm this booking?", function (result) {
        if (result) {
            window.location = href;
        }
    });
});
// Confirm Booking
$(document).on("click", ".cancel-booking", function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    return bootbox.confirm("Are you sure to cancel this booking?", function (result) {
        if (result) {
            window.location = href;
        }
    });
});
// View booking
$(".view-booking").click(function () {
    var data_id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: view_booking,
        cache: false,
        data: {
            "_token": $("input[name=_token]").val(),
            "id": data_id,
        },
        success: function (data) {
            $("#view-booking-detail").modal('show');
            $("#view-booking-content").empty();
            $("#view-booking-content").html(data.html);
        }
    });
});