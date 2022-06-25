<script>
    var baseUrl = '<?= base_url() ?>'

    function checkExt(ext) {
        switch (ext) {
            case "video/mp4":
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
                                <img class="d-block w-100" src='${baseUrl}/${data.files[key].url}' alt='${baseUrl}/${data.files[key].url}'>
                            </div>
                        `) 
                    } else {
                        containerCarouselInner.append(`
                            <div class="carousel-item ${i == 0 ? "active" : ""}">
                                <video src='${baseUrl}/${data.files[key].url}' controls> </video>
                            </div>
                        `) 
                    }
                    i++
                }
        
            },
            error: function(data) {
                alert(data)
            }
        });
    }

    function removePreview(e, i) {
        $(`#form-preview-files-${i}`).trigger("reset")
        $(`#preview-image-${i}`).attr("src", "https://via.placeholder.com/140")
        $(e).css("display", "none")
    }

    function changeProductFile(e, i) {
        var reader = new FileReader()
        var container = $(`#product-files-remove-${i}`).html('')

        var size = e.files[0].size / 1024 / 1024;

        if(size > 5) {
            $(`#form-preview-files-${i}`).trigger("reset")
            $(`#preview-image-${i}`).attr("src", "https://via.placeholder.com/140")
            Swal.fire({
                icon: 'info',
                title: `<h6>File size exceeds 5 MB</h6>`,
                text: '',
                showConfirmButton: true,
            })
            return
        }

        if(checkExt(e.files[0].type)) {
            reader.onload = function (e) {
                $(`#preview-image-${i}`).css("display", "none")
                $(`#preview-video-${i}`).css("display", "block")
                $(`#preview-video-${i}`).attr("src", e.target.result)
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
                background: red;" onclick="removePreview(this, ${i})"> x </a>`)
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

    function productsInstance() {
        return {
            images: [],
            test() {
                alert("test")
            },
            editProduct(uid) {
                $(".bd-edit-products-modal-lg").modal("toggle")
                var container = $(".box-preview-edited-images").html("")
                fetch(`<?= base_url() ?>/admin/products/${uid}/edit`)
                .then((response) => response.json())
                .then((json) => {
                    var res = json
                    var data = res.data
                    if(res.code == 200) {
                        $("#title-edit").val(data.title)
                        $("#description-edit").val(data.description)
                        this.images = data.images
                        for (var i = 0; i < this.images.length; i++) {
                            var n = container.children().length
                            container.append(
                                `<div @click=test() style="margin: 20px ${n == 0 ? '0' : '8px'}">
                                    <img src='${baseUrl}/${this.images[i]}' width="150" />
                                </div>`
                            )
                        }
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

            $(document).on("change", "#file-img", function() {
                var input = $(this)[0]
                var container = $('.box-preview-images').html('')
                var filename = ""
                var i = 0
                if(input.files && input.files[0]) {
                    if (parseInt(input.files.length) > 5){
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>You can only upload a maximum of 5 files!</h6>`,
                            text: '',
                            showConfirmButton: true,
                        })
                        return 
                    } else {
                        for (var f of input.files) {
                            i++
                            filename += `${f.name}, `
                            var reader = new FileReader()
                            reader.onload = function (e) {
                                var n = container.children().length
                                container.append(
                                    `<div class="preview-image-item ${n}" style="margin: 20px ${n == 0 ? '0' : '8px'}">
                                        <img src="${e.target.result}" width="130">
                                    </div>`
                                )
                            }
                            reader.readAsDataURL(f)
                        }
                        $("#file-img-label").text(filename)
                    }
                } 
                // var output = document.getElementById('output-file-img');
                // output.src = URL.createObjectURL(file);
                // output.onload = function() {
                //     URL.revokeObjectURL(output.src) 
                // }
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
                    return;
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
                    return;
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
                        title: `<h6>Berkas minimal 2 wajib diisi!</h6>`,
                        text: '',
                        showConfirmButton: true,
                    })
                    return 
                } 

                var fd = new FormData()
                fd.append("title", title)
                fd.append("description", description)
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
                        location.reload()
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>There was problem</h6>`,
                            text: '',
                            showConfirmButton: true,
                        })
                        $(".bd-create-products-modal-lg").modal("toggle")
                        $("#btn-create-a-product").text("Submit")
                    }
                });
            })
        })
    })(jQuery)
</script>