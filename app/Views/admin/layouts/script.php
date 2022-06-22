<script>
    (function ($) {
    'use strict';
        $(function () {
            $("#example1").DataTable({
                "responsive": true, 
                "lengthChange": false, 
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)')

            $(document).on("change", "#file-img", function() {
                var fd = new FormData()
                var files = $(this)[0].files
                fd.append('filesCount', files.length)
                for (var i = 0; i < files.length; i++) {
                    $("#file-img-label").text(files[i].name)
                    var output = document.getElementById('output-file-img');
                        output.src = URL.createObjectURL(files[i]);
                        output.onload = function() {
                            URL.revokeObjectURL(output.src) 
                        }
                    fd.append(`file-${i}`, files[i])
                }
                $.ajax({
                    url: `<?= base_url() ?>/admin/products/upload`,
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        var data = response.data
                    },
                });
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
                    success: function(response){
                        $("#btn-create-a-product").text("Submit")
                    },
                });
            })
        })
    })(jQuery)
</script>