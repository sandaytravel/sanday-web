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
                    },
                    identical: {
                        field: 'confirmpassword',
                        message: password_identical
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
                    },
                    identical: {
                        field: 'confirmpassword',
                        message: password_identical
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
//            ,
//            'subcategory[]': {
//                validators: {
//                    notEmpty: {
//                        message: subcategory_required
//                    },
//                    stringLength: {
//                        max: 50,
//                        message: subcategory_strlen
//                    }
//                }
//            }

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
            subcategory: {
                validators: {
                    notEmpty: {
                        message: subcategory_sel_required
                    }
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: city_sel_required
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
                selector: '.pacakge_title',
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
            },
            'booking_title[]': {
                validators: {
                    notEmpty: {
                        message: booking_title_required
                    }
                }
            },
            'booking_actual_price[]': {
                validators: {
                    notEmpty: {
                        message: booking_price
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

        // Add new field
        $('#activity-form').bootstrapValidator('addField', $packageTitle);
        $('#activity-form').bootstrapValidator('addField', $packagePrice);
        $packageDescription.summernote();
    }).on('click', '.remove-package', function () {
        var $row = $(this).closest('.row'),
                $packageTitle = $row.find('[name="package_title[]"]'),
                $packagePrice = $row.find('[name="package_actual_price[]"]');
        $row.remove();
        $('#activity-form').bootstrapValidator('removeField', $packageTitle);
        $('#activity-form').bootstrapValidator('removeField', $packagePrice);
    }).on('click', '.add-booking', function () {
        var $template = $(this).closest('.package-row').find('#booking_detail_clone'),
                $clone = $template
                .clone()
                .removeClass('hide')
                .removeAttr('id')
                .insertBefore($template),
                $bookingTitle = $(this).closest('.package-row').find('[name="booking_title[]"]'),
                $bookingPrice = $(this).closest('.package-row').find('[name="booking_actual_price[]"]');

        // Add new field
        $('#activity-form').bootstrapValidator('addField', $bookingTitle);
        $('#activity-form').bootstrapValidator('addField', $bookingPrice);
    }).on('click', '.remove-booking', function () {
        var $row = $(this).closest('.booking-details'),
                $packageTitle = $row.find('[name="booking_title[]"]'),
                $packagePrice = $row.find('[name="booking_actual_price[]"]');
        // Remove element containing the option
        $row.remove();
        // Remove field
        $('#activity-form').bootstrapValidator('removeField', $packageTitle);
        $('#activity-form').bootstrapValidator('removeField', $packagePrice);
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
            subcategory: {
                validators: {
                    notEmpty: {
                        message: subcategory_sel_required
                    }
                }
            },
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
// For user deactivations
$('.user-status').on('ifUnchecked', function (event) {
    var id = $(this).attr('data-id');
    var data_type = $(this).attr('data-type');
    $.ajax({
        type: "PUT",
        url: changeUserStatus,
        cache: false,
        data: {
            "_token": $("input[name=_token]").val(),
            "id": id,
            "status": "Inactive",
            "data_type": data_type
        },
        success: function (data) {
            $(".status-message").text(data.status);
            $(".status-flash-div").css('display', 'block');
            $(".status-flash-div").delay(5000).fadeOut('slow');
            $(".status-flash-div").removeClass('hide');
        }
    });
});
// For user activations
$('.user-status').on('ifChecked', function (event) {
    var id = $(this).attr('data-id');
    var data_type = $(this).attr('data-type');
    $.ajax({
        type: "PUT",
        url: changeUserStatus,
        cache: false,
        data: {
            "_token": $("input[name=_token]").val(),
            "id": id,
            "status": "Active",
            "data_type": data_type
        },
        success: function (data) {
            $(".status-message").text(data.status);
            $(".status-flash-div").css('display', 'block');
            $(".status-flash-div").delay(5000).fadeOut('slow');
            $(".status-flash-div").removeClass('hide');
        }
    });
});

$(document).on('click', '#reset-search-product', function () {
    location.reload();
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
    $("#update-category").modal('show');
    $("#update-category").find('#category_id').val(id);
    $("#update-category").find('#category').val(category);
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
    $("#update-policy-modal").modal('show');
    $("#update-policy-modal").find('#policy_id').val(id);
    $("#update-policy-modal").find('#policy-name').val(name);
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
            $("#activity-form").bootstrapValidator("revalidateField", 'subcategory');
        }
    });
});
/* Change Activity Status */
$('.activity-status').on('ifUnchecked', function (event) {
    var id = $(this).attr('data-id');
    var status = "Inactive";
    changeActivityStatus(id, status);
});
$('.activity-status').on('ifChecked', function (event) {
    var id = $(this).attr('data-id');
    var status = "Active";
    changeActivityStatus(id, status);
});
function changeActivityStatus(id, status) {
    $.ajax({
        type: "PUT",
        url: changeActivityStatusUrl,
        cache: false,
        data: {
            "_token": $("input[name=_token]").val(),
            "id": id,
            "status": status,
        },
        success: function (data) {
            $(".status-message").text(data.status);
            $(".status-flash-div").css('display', 'block');
            $(".status-flash-div").delay(5000).fadeOut('slow');
            $(".status-flash-div").removeClass('hide');
        }
    });
}