// Mix of jQuery and native Javascript - CropperJS is a Javascript library, but jQuery is needed in some cases.
window.addEventListener('DOMContentLoaded', function() {
    var $wrapper = '.pp_upload ';
    var $image = document.querySelector($wrapper + '.pp_modal_image');
    var $input = document.querySelector($wrapper + '.pp_input');
    var $actions = document.querySelector($wrapper + '.pp_modal_image_edit');
    var $newImageContainer = $('.pp_new');
    var $newImage = $('.pp_new img');
    var $btnCrop = $($wrapper + '.pp_crop');
    var $hidden =  $($wrapper + '.pp_data');
    var $modal = $($wrapper + '.pp_modal');
    var $options = {
        aspectRatio: 1,
        viewMode: 0,
        dragMode: 'move',
    };
    var $cropper;

    $($wrapper + '[data-toggle="tooltip"]').tooltip();

    $input.addEventListener('change', function(e) {
        var files = e.target.files;
        var done = function(url) {
            $input.value = '';
            $image.src = url;
            $modal.modal('show');
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $modal.on('shown.bs.modal', function() {
        $cropper = new Cropper($image, $options);
    }).on('hidden.bs.modal', function() {
        $cropper.destroy();
        $cropper = null;
    });

    $actions.onchange = function(event) {
        var e = event || window.event;
        var target = e.target || e.srcElement;
        var cropBoxData;
        var canvasData;
        var isCheckbox;
        var isRadio;
        if (!$cropper) {
            return;
        }

        if (target.tagName.toLowerCase() === 'label') {
            target = target.querySelector('input');
        }

        isCheckbox = target.type === 'checkbox';
        isRadio = target.type === 'radio';

        if (isCheckbox || isRadio) {
            if (isCheckbox) {
                $options[target.name] = target.checked;
                cropBoxData = $cropper.getCropBoxData();
                canvasData = $cropper.getCanvasData();

                $options.ready = function() {
                    console.log('ready');
                    $cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                };
            } else {
                $options[target.name] = target.value;
                $options.ready = function() {
                    console.log('ready');
                };
            }

            // Restart
            $cropper.destroy();
            $cropper = new Cropper($image, $options);
        }
    };

    $actions.onclick = function(event) {
        var e = event || window.event;
        var target = e.target || e.srcElement;
        var cropped;
        var result;
        var $input;
        var data;

        if (!$cropper) {
            return;
        }

        while (target !== this) {
            if (target.getAttribute('data-method')) {
                break;
            }

            target = target.parentNode;
        }

        if (target === this || target.disabled || target.className.indexOf('disabled') > -1) {
            return;
        }

        data = {
            method: target.getAttribute('data-method'),
            target: target.getAttribute('data-target'),
            option: target.getAttribute('data-option') || undefined,
            secondOption: target.getAttribute('data-second-option') || undefined
        };

        cropped = $cropper.cropped;

        if (data.method) {
            if (typeof data.target !== 'undefined') {
                $input = document.querySelector(data.target);

                if (!target.hasAttribute('data-option') && data.target && $input) {
                    try {
                        data.option = JSON.parse($input.value);
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            }

            switch (data.method) {
                case 'rotate':
                    if (cropped && $options.viewMode > 0) {
                        $cropper.clear();
                    }

                    break;

                case 'getCroppedCanvas':
                    try {
                        data.option = JSON.parse(data.option);
                    } catch (e) {
                        console.log(e.message);
                    }

                    if (uploadedImageType === 'image/jpeg') {
                        if (!data.option) {
                            data.option = {};
                        }

                        data.option.fillColor = '#fff';
                    }

                    break;
            }

            result = $cropper[data.method](data.option, data.secondOption);

            switch (data.method) {
                case 'rotate':
                    if (cropped && $options.viewMode > 0) {
                        $cropper.crop();
                    }

                    break;

                case 'scaleX':
                case 'scaleY':
                    target.setAttribute('data-option', -data.option);
                    break;

                case 'getCroppedCanvas':
                    if (result) {
                        // Bootstrap's Modal
                        $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

                        if (!download.disabled) {
                            download.download = uploadedImageName;
                            download.href = result.toDataURL(uploadedImageType);
                        }
                    }

                    break;

                case 'destroy':
                    $cropper = null;

                    if (uploadedImageURL) {
                        URL.revokeObjectURL(uploadedImageURL);
                        uploadedImageURL = '';
                        $image.src = originalImageURL;
                    }

                    break;
            }

            if (typeof result === 'object' && result !== $cropper && $input) {
                try {
                    $input.value = JSON.stringify(result);
                } catch (e) {
                    console.log(e.message);
                }
            }
        }
    };

    $($btnCrop).on('click', function() {
        var canvas;
        var dataURL;

        if ($cropper) {
            canvas = $cropper.getCroppedCanvas({
                width: $profilepicture_size,
                height: $profilepicture_size,
            });
            dataURL = canvas.toDataURL();
            $hidden.val(dataURL).trigger('change');
            $newImageContainer.css('display', 'block');
            $newImage.attr('src', dataURL);
        }

        $modal.modal('hide');
    });

    $('.pp_file_input').on('change', function() {
        // Firefox bug fix
        $(this).on( 'focus', function(){ $(this).addClass( 'has-focus' ); }).on( 'blur', function(){ $(this).removeClass( 'has-focus' ); });
    });

    $hidden.on('change', function() {
        if($(this).val())
            $('label[for=pp_input_]').find('span').html($profilepicture_uploaded);
        else
            $('label[for=pp_input_]').find('span').html($profilepicture_not_uploaded);
    });
});
