$(function() {
    var $wrapper = '.pp_upload';
    var $modalContainer = '.pp_modal';
    var $image = document.querySelector($wrapper + ' .pp_modal_image');
    var $input = document.querySelector($wrapper + ' .pp_input');

    if($input === null) {
        return;
    }

    var $actions = document.querySelector($wrapper + ' .pp_modal_image_edit');
    var $newImageContainer = document.querySelector('.pp_new');
    var $newImage = $('.pp_new img');
    var $btnCrop = $($wrapper + ' .pp_crop');
    var $hidden =  $($wrapper + ' .pp_data');
    var $options = {
        aspectRatio: 1,
        viewMode: 0,
        dragMode: 'move',
    };
    var $cropper;

    var $modal = new Custombox.modal({
        content: {
            effect: 'fadein',
            target: $modalContainer
        }
    });

    $input.addEventListener('change', function(e) {
        var files = e.target.files;
        var done = function(url) {
            $input.value = '';
            $image.src = url;
            $modal.open();
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

    document.addEventListener('custombox:content:open', function() {
        document.querySelector($modalContainer).style.display = 'block';
        $cropper = new Cropper($image, $options);
    });

    document.addEventListener('custombox:overlay:close', function() {
        document.querySelector($modalContainer).style.display = 'none';
        $cropper.destroy();
        $cropper = null;
    });

    $actions.onclick = function(event) {
        var e = event || window.event;
        var target = e.target || e.srcElement; console.info(target);
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
                width: $profilepic_size,
                height: $profilepic_size,
            });
            dataURL = canvas.toDataURL();
            $hidden.val(dataURL).trigger('change');
            $newImageContainer.style.display = 'block';
            $newImage.attr('src', dataURL);
        }

        Custombox.modal.closeAll();
    });

    $('.pp_file_input').on('change', function() {
        $(this).on( 'focus', function(){ $(this).addClass( 'has-focus' ); }).on( 'blur', function(){ $(this).removeClass( 'has-focus' ); });
    });

    $hidden.on('change', function() {
        if($(this).val()) {
            $('label[for=pp_input_]').find('span').html($profilepic_uploaded);
        } else {
            $('label[for=pp_input_]').find('span').html($profilepic_not_uploaded);
        }
    });
});
