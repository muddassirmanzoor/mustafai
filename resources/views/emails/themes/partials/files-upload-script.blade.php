<script>

    let fileNames = []
    let createCaseFilesCounter = 1
    let editCaseFilesCounter = 1
    let validFileTypes = [
        'image/jpg', 'image/jpeg', 'image/png', 'image/bmp', 'image/svg', 'image/gif', 'image/webp',
        'video/mp4', 'video/mov', 'video/wmv', 'video/avi', 'video/flv', 'video/swf', 'video/mkv', 'video/webm'
    ];
    let videoFileTypes = ['video/mp4', 'video/mov', 'video/wmv', 'video/avi', 'video/flv', 'video/swf', 'video/mkv', 'video/webm']

    $(document).on("change", '.post-input-file', function (e) {
        var files = e.target.files
        filesLength = files.length;

        for (i = 0; i < filesLength; i++) {
            var reader = new FileReader();
            let fileObject = files[i]

            if (createCaseFilesCounter > 5) {
                Swal.fire('You have reached max file limit! 🙃', '', 'error')
                break
            }

            // if file length exceeds 5, abort whole uploading operation
            if (filesLength > 5) {
                Swal.fire('You can upload only 5 files at a time! 😔', '', 'error')
                break
            }
            // if file type is not valid then abort loop iteration
            if (! validFileTypes.includes(fileObject.type)) {
                Swal.fire('File is not valid that you are trying to upload! 😔', '', 'error')
                continue
            }
            // if file size greater than 10mb abort loop iteration
            if (fileObject.size > 10000000) {
                Swal.fire('Files greater than 10mb are aborted! 🙃', '', 'error')
                continue
            }

            let fileName = files[i].name
            // if file is valid, track the number of files counter
            createCaseFilesCounter ++;


            $($.parseHTML(`<div class="post-upload-image" data-image-name=${fileName}>
                        <span class="image-dell" data-image-name=${fileName} onclick="removeImage(this)"><i class="fa fa-times" aria-hidden="true"></i></span>
                          <img src=${! videoFileTypes.includes(fileObject.type) ? window.URL.createObjectURL(fileObject) : 'images/video-icon.png' } class="remove_image muli-images-upload">
                        </div>
                        `)).appendTo('#newRow');
            reader.readAsDataURL(files[i]);

            fileNames.push(fileName)
            // $('#new_files').val(fileNames)
            $('#tracking_files').val(fileNames)

            // hide previous 'input type file' and append another input type file
            if (i === filesLength -1) {
                $('.hiddenFileInput').hide()
                $('.file_wraping_div').append(`
                        <span class="hiddenFileInput"><input id="files" type="file" name="files[]" class="form-control m-input post-input-file" accept="image/*" multiple style="height:146%"></span>
                        `)
            }
        }
    });

    function removeImage(_this) {
        let imageName = $(_this).attr('data-image-name')

        // remove file from array
         _.remove(fileNames, function(fileName) {
            return fileName === imageName
        });
        $(_this).parent().remove()
        // override input, that is to submit with form
        $('#tracking_files').val(fileNames)
        // on removal of file, decrement counter
        createCaseFilesCounter--;
    }
</script>


<!-- in case of edit -->

<script>
    let editFileNames = []

    $(document).on("change", '.post-input-file-edit', function (e) {
        var files = e.target.files
        filesLength = files.length;

        for (i = 0; i < filesLength; i++) {
            var reader = new FileReader();
            let fileObject = files[i]

            if (editCaseFilesCounter > 5) {
                Swal.fire('You have reached max file limit! 🙃', '', 'error')
                break
            }

            // if file length exceeds 5, abort whole uploading operation
            if (filesLength > 5) {
                Swal.fire('You can upload only 5 files at a time! 😔', '', 'error')
                break
            }
            // if file type is not valid then abort loop iteration
            if (! validFileTypes.includes(fileObject.type)) continue
            // if file size greater than 10mb abort loop iteration
            if (fileObject.size > 10000000) {
                Swal.fire('Files greater than 10mb are aborted! 🙃', '', 'error')
                continue
            }

            // if file is valid, track the number of files counter
            editCaseFilesCounter++;

            let fileName = files[i].name

            $($.parseHTML(`
                    <div class="post-upload-image" data-image-name=${fileName}">
                      <span class="image-dell" onclick="editRemoveImage(this)"><i class="fa fa-times" aria-hidden="true"></i></span>
                      <img src=${! videoFileTypes.includes(fileObject.type) ? window.URL.createObjectURL(fileObject) : 'images/video-icon.png' } class="edit_remove_image muli-images-upload" data-image-name=${fileName}>
                    </div>
`)).appendTo('#editNewRow');
            reader.readAsDataURL(files[i]);

            editFileNames.push(fileName)
            $('#new_files').val(editFileNames)

            // hide previous 'input type file' and append another input type file
            if (i === filesLength -1) {
                $('.hiddenFileInputEdit').hide()
                $('.file_wraping_div_edit').append(`
                        <span class="hiddenFileInputEdit">
                         <input id="files" type="file" name="files[]" class="form-control m-input post-input-file-edit" multiple accept="image/*">
                        </span>
                        `)
            }
        }
    });

    function editRemoveImage(_this) {
        let imageName = $(_this).attr('data-image-name')

        // remove old file if exists
        $('[data-remove-old-file="' + imageName + '"]').remove()

        // remove file from array
        _.remove(fileNames, function(fileName) {
            return fileName === imageName
        });
        $(_this).parent().remove()
        // override input, that is to submit with form
        $('#tracking_files_edit').val(editFileNames)
        // on removal of file, decrement counter
        editCaseFilesCounter--
    }

    $("#editPostModal").on('hide.bs.modal', function(){
        editCaseFilesCounter = 1
    });
    $("#editPostModal").on('show.bs.modal', function(){
        setTimeout(() => {
            if ($('.total_files_counter').val() !== undefined) {
                editCaseFilesCounter = $('.total_files_counter').val()
            }
        }, 1000)
    });
</script>
