<script>
    var baseUrl = '<?= base_url() ?>'

    function checkExt(ext) {
        switch (ext) {
            case "video/mp4":
                return true;
            break;
            case "video/mpeg":
                return true;
            break;
            case "image/png":
                return false;
            break;
            case "image/jpg":
                return false;
            break; 
            case "image/gif":
                return false;
            break;   
            case "image/jpeg":
                return false;
            break;            
            default:
            break;
        }
        return false;
    }

    function showFile(slug) {
        $(".bd-show-file-modal-lg").modal("toggle")
        var containerCarouselIndicators = $(".show-carousel-indicators-file-wrapper").html('')
        var containerCarouselInner = $(".show-carousel-inner-file-wrapper").html('')
        $.ajax({
            url: `<?= base_url() ?>/admin/products/${slug}/show-file`,
            type: 'GET',
            contentType: false,
            processData: false,
            success: function(response) {
                var data = response.data
                var i = 0
                for (var key in data.files) {
                    containerCarouselIndicators.append(`
                        <li data-slide-to=${i} class="${i == 0 ? "active" : ""}"></li>
                    `)
                    if(data.files[key].type == "image") {
                        containerCarouselInner.append(`
                            <div class="carousel-item ${i == 0 ? "active" : ""}">
                                <img class="d-block" style="width: 50%; margin: auto;" src='${baseUrl}/${data.files[key].url}' alt='${baseUrl}/${data.files[key].url}'>
                                <a style='position: absolute; z-index: 99999; top: 12px; right: 20px;' href='${baseUrl}/${data.files[key].url}' download="proposed_file_name">Download</a>
                            </div>
                        `) 
                    } else {
                        containerCarouselInner.append(`
                            <div class="carousel-item ${i == 0 ? "active" : ""}">
                                <video class="d-block" src='${baseUrl}/${data.files[key].url}' style="width: 50%; margin: auto;" controls> </video>
                                <a href='${baseUrl}/${data.files[key].url}' download="proposed_file_name">Download</a>
                            </div>
                        `) 
                    }
                    i++
                }
        
            },
            error: function(data) {
                console.log(data)
            }
        });
    }

    function removePreview(e, i) {
        $(`#form-preview-files-${i}`).trigger("reset")
        $(`#preview-image-${i}`).attr("src", "https://via.placeholder.com/1080x1080")
        $(`#preview-video-${i}`).replaceWith(`
            <img id="preview-image-${i}" src="https://via.placeholder.com/1080x1080" width="140">
        `)
        $(e).css("display", "none")
    }

    function changeProductFile(e, i) {
        var reader = new FileReader()
        var container = $(`#product-files-remove-${i}`).html('')

        var size = e.files[0].size / 1024 / 1024;

        if(size > 100) {
            $(`#preview-image-${i}`).attr("src", "https://via.placeholder.com/1080x1080")
            Swal.fire({
                icon: 'info',
                title: `<h6>File size exceeds 100 MB</h6>`,
                text: '',
                showConfirmButton: true,
            })
            return
        }

        if(checkExt(e.files[0].type)) {
            reader.onload = function (e) {
                $(`#preview-image-${i}`).replaceWith(`
                    <video id="preview-video-${i}" src=${e.target.result} width="140" controls>
                        Your browser does not support HTML video.
                    </video>
                `)
                container.append(`<a href="javascript:void(0)" style="
                    color: white; 
                    text-align: center;
                    display: inline-block;
                    position: absolute;
                    width: 20px;
                    right: 0;
                    top: 0;
                    background: red;" onclick="removePreview(this, ${i})"> x </a>`
                )
            }       
            reader.readAsDataURL(e.files[0])
        } else {
            reader.onload = function (e) {
                $(`#preview-image-${i}`).attr("src", e.target.result)
                container.append(`<a href="javascript:void(0)" style="
                    color: white; 
                    text-align: center;
                    display: inline-block;
                    position: absolute;
                    width: 20px;
                    right: 0;
                    top: 0;
                    background: red;" onclick="removePreview(this, ${i})"> x </a>`
                )
            }       
            reader.readAsDataURL(e.files[0])
        }
    }


    function deleteProduct(slug) {
        Swal.fire({
            icon: 'info',
            title: `<h6>Hapus produk?</h6>`,
            text: '',
            showCloseButton: true,
            showCancelButton: true,
            reverseButtons: true,
            showConfirmButton: true,
            backdrop: 'swal2-backdrop-hide', 
            preConfirm: (_) => {
                return fetch(`<?= base_url() ?>/admin/products/${slug}/delete`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                }).then(async (response) => {
                    var data = await response.json()
                    if(data.code != 200) {
                        Swal.fire({
                            icon: '',
                            title: `<h6>There was problem\n\n(${data.message})</h6>`,
                            text: '',
                            showConfirmButton: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload()
                            }
                        })
                        return;
                    } else {
                        Swal.fire({
                            icon: '',
                            title: `<h6>${data.message}</h6>`,
                            text: '',
                            showConfirmButton: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload()
                            }
                        })
                    }
                }).catch((_) => {
                    Swal.fire({
                        icon: '',
                        title: `<h6>There was problem</h6>`,
                        text: '',
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload()
                        }
                    })
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        })
    }

    function uuidv4() {
        return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        )
    }

    function productsInstance() {
        return {
            files: [],
            removeFiles: [],
            changeProductFileEdit(e, i) {
                var reader = new FileReader()
                var container = $(`#product-files-remove-edit-${i}`).html('')

                var size = e.target.files[0].size / 1024 / 1024;

                if(size > 100) {
                    $(`#preview-image-edit-${i}`).attr("src", "https://via.placeholder.com/1080x1080")
                    Swal.fire({
                        icon: 'info',
                        title: `<h6>File size exceeds 100 MB</h6>`,
                        text: '',
                        showConfirmButton: true,
                    })
                    return
                }

                if(checkExt(e.target.files[0].type)) {
                    reader.onload = function (e) {
                        $(`#preview-image-edit-${i}`).replaceWith(
                            `<video id="preview-video-edit-${i}" src=${e.target.result} width="140" controls>
                                Your browser does not support HTML video.
                            </video>`
                        )
                    }       
                    reader.readAsDataURL(e.target.files[0])
                } else {
                    reader.onload = function (e) {                     
                        $(`#preview-image-edit-${i}`).attr("src", e.target.result)
                        container.append(`
                            <a href="javascript:void(0)" style="
                                color: white; 
                                text-align: center;
                                display: inline-block;
                                position: absolute;
                                width: 20px;
                                right: 0;
                                top: 0;
                                background: red;" @click="removePreviewEdit($event, ${i})"> x 
                            </a>
                        `)
                    }       
                    reader.readAsDataURL(e.target.files[0])
                }
                var filesEdit = JSON.parse($("#files-edit").val())
                filesEdit.push({
                    "uid": $(`#product-files-edit-${i}`)[0].dataset.uid
                })
                $("#files-edit").val(JSON.stringify(filesEdit))
            },
            removePreviewEdit(e, i) {
                this.removeFiles.push({
                    "uid": $(`#product-files-edit-${i}`)[0].dataset.uid,
                    "url": $(`#product-files-edit-${i}`)[0].dataset.url
                })
                var filesEdit = JSON.parse($("#files-edit").val())
                $("#files-edit").val(JSON.stringify(filesEdit.filter(el => el.uid !== $(`#product-files-edit-${i}`)[0].dataset.uid)))
                $(`#form-preview-files-edit-${i}`).trigger("reset")
                $(`#preview-image-edit-${i}`).attr("src", "https://via.placeholder.com/1080x1080")
                $(`#preview-video-edit-${i}`).replaceWith(`<img id="preview-image-edit-${i}" src="https://via.placeholder.com/1080x1080" width="140">`)
                $(e.target).css("display", "none")
            },
            updateProduct(e) {
                var filesUpdate = []
                var slug = $("#slug").val()
                var title = $("#title-edit").val()
                var description = $("#description-edit").val()
                var filesEdit = JSON.parse($("#files-edit").val())

                if(title.trim() == "") {
                    Swal.fire({
                        icon: 'info',
                        title: `<h6>Judul wajib diisi!</h6>`,
                        text: '',
                        showConfirmButton: true,
                    })
                    return 
                }
                if(description.trim() == "") {
                    Swal.fire({
                        icon: 'info',
                        title: `<h6>Deskripsi wajib diisi!</h6>`,
                        text: '',
                        showConfirmButton: true,
                    })
                    return 
                }
                
                if(filesEdit.length < 2) {
                    Swal.fire({
                        icon: 'info',
                        title: `<h6>Berkas minimal 2 data!</h6>`,
                        text: '',
                        showConfirmButton: true,
                    })
                    return 
                }

                var fd = new FormData()
                fd.append("title", title.trim())
                fd.append("description", description.trim())
                fd.append("filesRemove", JSON.stringify(this.removeFiles))
                fd.append("filesCount", filesEdit.length)
                for (var i = 0; i < filesEdit.length; i++) {
                    if(typeof $(`#product-files-edit-${i}`)[0].files[0] != "undefined") {
                        fd.append(`filesUpdateUid-${i}`, $(`#product-files-edit-${i}`)[0].dataset.uid)
                        fd.append(`filesUpdate-${i}`, $(`#product-files-edit-${i}`)[0].files[0])
                    }
                }
                $(e.target).text("...")
                $.ajax({
                    url: `<?= base_url() ?>/admin/products/${slug}/files/delete`,
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(data) {},
                    error: function(data) {
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>There was problem!</h6>`,
                            text: '',
                            showConfirmButton: true,
                        })
                    }
                })
                $.ajax({
                    url: `<?= base_url() ?>/admin/products/${slug}/update`,
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $(e.target).text("Submit")
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>${data.message}</h6>`,
                            text: '',
                            showConfirmButton: true,
                        })
                        location.reload()
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")")
                        if(typeof err != "undefined") {
                            Swal.fire({
                                icon: 'info',
                                title: `<h6>${err.message}</h6>`,
                                text: '',
                                showConfirmButton: true,
                            })
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: `<h6>There was problem</h6>`,
                                text: '',
                                showConfirmButton: true,
                            })
                        }
                        $(e.target).text("Submit")
                    }
                })
            },
            editProduct(uid) {
                $(".bd-edit-products-modal-lg").modal("toggle")
                $("#slug").val(uid)
                var container = $(".wrapper-preview-files-edited").html('')
                fetch(`<?= base_url() ?>/admin/products/${uid}/edit`)
                .then((response) => response.json())
                .then((json) => {
                    var res = json
                    var data = res.data
                    if(res.code == 200) {
                        $("#title-edit").val(data[0].title)
                        $("#description-edit").val(data[0].description)
                        $("#files-edit").val(JSON.stringify(data[0].files))
                        this.files = data[0].files  
                        for (var i = 0; i < 5; i++) {
                            if(typeof this.files[i] == "undefined") {
                                container.append(`
                                    <form id="form-preview-files-edit-${i}" style="margin: 0px 4px 0px 4px;">
                                        <label class="product-files-label" for="product-files-edit-${i}">
                                            <div id="wrapper-product-files">
                                                <img id="preview-image-edit-${i}" src="https://via.placeholder.com/1080x1080" width="140">
                                                <div class="products-files-remove" id="product-files-remove-edit-${i}"> </div>
                                            </div>
                                        </label>
                                        <input type="file" accept="image/*,video/*" @change="changeProductFileEdit($event, ${i})" data-url="-" data-uid="${uuidv4()}" name="file" id="product-files-edit-${i}" style="display:none">     
                                    </form>
                                `)
                            } else {
                                container.append(`
                                    <form id="form-preview-files-edit-${i}" style="margin: 0px 4px 0px 4px;">
                                        <label class="product-files-label" for="product-files-edit-${i}">
                                            <div id="wrapper-product-files">
                                                <template x-if="${this.files[i].type == 'image'}">
                                                    <img id="preview-image-edit-${i}" src="${baseUrl}/${this.files[i].url}" width="140">
                                                </template>
                                                <template x-if="${this.files[i].type == 'video'}">
                                                    <video id="preview-video-edit-${i}" src="${baseUrl}/${this.files[i].url}" width="140" controls>
                                                        Your browser does not support HTML video.
                                                    </video>
                                                </template>
                                                <div class="products-files-remove" id="product-files-remove-edit-${i}">
                                                <a href="javascript:void(0)" style="
                                                    color: white; 
                                                    text-align: center;
                                                    display: inline-block;
                                                    position: absolute;
                                                    width: 20px;
                                                    right: 0;
                                                    top: 0;
                                                    background: red;" @click="removePreviewEdit($event, ${i})"> x </a>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="file" accept="image/*,video/*" @change="changeProductFileEdit($event, ${i})" data-url="${this.files[i].url}" data-uid="${this.files[i].uid}" name="file" id="product-files-edit-${i}" style="display:none">     
                                    </form>
                                `)
                            }
                        }
                    } 
                     else {
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>There was problem!</h6>`,
                            text: '',
                            showConfirmButton: true,
                        })
                        return 
                    }
                })
                .catch((_) => {})
            },
        }
    }

    (function ($) {
    'use strict';
        $(function () {

            var table = $("#datatables-products").DataTable({
                "responsive": true, 
                "processing": true,
                "serverSide": true,
                dom: 'Bfrtipl',
                buttons:  ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "ajax": {
                    url: '<?= base_url() ?>/admin/products/init-datatables-products',
                    dataType: "JSON",
                    type: "POST"
                },
                columns: [
                    {
                        data: "no",
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: "title"
                    },
                    {
                        data: "description"
                    },
                    {
                        data: "files"
                    },
                    {
                        data: "uploadby"
                    },
                    {
                        data: "edit",
                        searchable: false,
                        orderable: false,
                    }, 
                    {
                        data: "delete",
                        searchable: false,
                        orderable: false,
                    }
                ]
            })
            
            $(document).on("click", "#btn-create-a-product", function(e) {
                e.preventDefault()
                
                var title = $("#title").val()
                if(title.trim() == "") {
                    $("#title").focus()
                    Swal.fire({
                        icon: 'info',
                        title: `<h6>Judul wajib diisi!</h6>`,
                        text: '',
                        showConfirmButton: true,
                    })
                    return
                }
                var description = $("#description").val()
                if(description.trim() == "") {
                    $("#description").focus()
                    Swal.fire({
                        icon: 'info',
                        title: `<h6>Deskripsi wajib diisi!</h6>`,
                        text: '',
                        showConfirmButton: true,
                    })
                    return
                }

                var filesData = []
                for (var i = 0; i < 5; i++) {
                    var files = $(`#product-files-${i}`)[0].files
                    filesData.push(files) 
                }

                var files = filesData.filter(function(file) {
                    if(file.length == 1) {
                        return true
                    } else {
                        return false
                    }
                })

                if(files.length < 2) {
                    Swal.fire({
                        icon: 'info',
                        title: `<h6>Berkas minimal 2 data!</h6>`,
                        text: '',
                        showConfirmButton: true,
                    })
                    return 
                } 

                var fd = new FormData()
                fd.append("title", title.trim())
                fd.append("description", description.trim())
                fd.append('filesCount', files.length)
                for (var i = 0; i < files.length; i++) {
                    fd.append(`file-${i}`, files[i][0])
                }
          
                $(this).text("...")
                $.ajax({
                    url: `<?= base_url() ?>/admin/products/store`,
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>${data.message}</h6>`,
                            text: '',
                            showConfirmButton: true,
                        })
                        $(".bd-create-products-modal-lg").modal("toggle")
                        $("#btn-create-a-product").text("Submit")
                        // location.reload()
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")")
                        if(typeof err != "undefined") {
                            Swal.fire({
                                icon: 'info',
                                title: `<h6>${err.message}</h6>`,
                                text: '',
                                showConfirmButton: true,
                            })
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: `<h6>There was problem</h6>`,
                                text: '',
                                showConfirmButton: true,
                            })
                        }
                        $(".bd-create-products-modal-lg").modal("toggle")
                        $("#btn-create-a-product").text("Submit")
                    }
                })
            })
        })
    })(jQuery)
</script>