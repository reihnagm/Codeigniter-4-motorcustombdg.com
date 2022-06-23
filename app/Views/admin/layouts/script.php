<script>
    
    function deleteProduct(uid) {
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
                return fetch(`<?= base_url() ?>/admin/products/${uid}/delete`, {
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
                        data: "img"
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
                var fd = new FormData()
                var input = $(this)[0]
                var container = $('.box-preview-images').html('')
                var filename = ""
                var i = 0
                if(input.files && input.files[0]) {
                    if (parseInt(input.files.length) > 5){
                        alert("You can only upload a maximum of 5 files");
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
                            fd.append(`file-${i}`, f)
                        }
                        $("#file-img-label").text(filename)
                    }
                }  
                
                // reader.readAsDataURL(file)

                // var output = document.getElementById('output-file-img');
                // output.src = URL.createObjectURL(file);
                // output.onload = function() {
                //     URL.revokeObjectURL(output.src) 
                // }
            })

            $(document).on("click", "#btn-create-a-product", function(e) {
                e.preventDefault()
                var title = $("#title").val()
                var description = $("#description").val()
                var files = $("#file-img")[0].files
                var fd = new FormData()
                fd.append('filesCount', files.length)
                fd.append("title", title)
                fd.append("description", description)
                for (var i = 0; i < files.length; i++) {
                    fd.append(`file-${i}`, files[i])
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
                });
            })
        })
    })(jQuery)
</script>