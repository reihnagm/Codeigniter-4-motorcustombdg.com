<?php 
    use Config\Services;

    $request = Services::request();
    $session = Services::session();

    $segment = $request->uri->getSegment(1);
?>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- Alpine -->
<script src="https://unpkg.com/alpinejs" defer></script>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous">
</script>

<!-- Toast Jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!--  Animejs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js" integrity="sha512-z4OUqw38qNLpn1libAN9BsoDx6nbNFio5lA6CuTp9NlK83b89hgyCVq+N5FdBJptINztxn1Z3SaKSKUS5UP60Q==" crossorigin="anonymous" referrerpolicy="no-referrer">
</script>

<!-- Owljs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js" integrity="sha512-gY25nC63ddE0LcLPhxUJGFxa2GoIyA5FLym4UJqHDEMHjp8RET6Zn/SHo1sltt3WuVtqfyxECP38/daUc/WVEA==" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

<script>
    var productImg = $("#productImg")

    for (let i = 0; i < 5; i++) {
        $(`#small-img-${i}`).click(function() {
            var type = $(this)[0].dataset.type
            if(type == "video") {
                $("#productImg").replaceWith(`<video src="${$(this).attr("src")}" width="100%" id="productVid" controls> </video>`)
                $("#productVid").replaceWith(`<video src="${$(this).attr("src")}" width="100%" id="productVid" controls> </video>`)
            } else {
                $("#productImg").replaceWith(`<img src="${$(this).attr("src")}" width="100%" id="productImg">`)
                $("#productVid").replaceWith(`<img src="${$(this).attr("src")}" width="100%" id="productImg">`)
            }
        })
    }

    // API
    var baseUrl = '<?= base_url() ?>';

    function info(msg) {
        $.toast({
            heading: 'Information',
            text : `${msg}`,
            bgColor : '#ce1230',            
            textColor : '#ffffff',   
            icon: 'error',         
            allowToastClose : false,                
            textAlign : 'left',        
            position : 'top-right',
            loaderBg: '#ffffff'
        })
    }

    function ValidateEmail(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return (true)
    }
        return (false)
    }

    $("#post-login-btn").click(async function(e) {
        e.preventDefault()

        var email = $("#email").val()
        var password = $("#password").val()

        if(email.trim() == "") {
            info("E-mail Address wajib diisi!")
            return; 
        }

        if(!ValidateEmail(email)) {
            info("Format E-mail Address salah! Contoh : johndoe@gmail.com")
            return; 
        }

        $(this).text("...")
        try {
            var response = await fetch(`${baseUrl}/auth/post-login`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    password: password,                   
                })
            })    
            var data = await response.json()
            if(data.code != 200) {
                Swal.fire({
                    icon: 'info',
                    title: `<h6>(${data.code}) - ${data.message}</h6>`,
                    text: '',
                    showConfirmButton: true,
                })
            } else {
                Swal.fire({
                    icon: 'info',
                    title: `<h6>${data.message}</h6>`,
                    text: '',
                    showConfirmButton: true,
                })
                location.href = baseUrl
            }
        } catch(_) {
            Swal.fire({
                icon: 'info',
                title: `<h6>There was problem</h6>`,
                text: '',
                showConfirmButton: true,
                preConfirm: (_) => {
                    location.reload()
                },
            })
        } finally {
            $(this).text("Login")
        }
    })

    $("#post-register-btn").click( async function(e){
        e.preventDefault()

        var username = $("#username").val()
        var email = $("#email").val()
        var password = $("#password").val()
        
        if(username.trim() == "") {
            info("Username wajib diisi!")
            return; 
        }

        if(email.trim() == "") {
            info("E-mail Address wajib diisi!")
            return; 
        }

        if(!ValidateEmail(email)) {
            info("Format E-mail Address salah! Contoh : johndoe@gmail.com")
            return; 
        }

        if(password.trim() == "") {
            info("Password wajib diisi!")
            return;
        }

        $(this).text("...")
        try {
            var response = await fetch(`${baseUrl}/auth/post-register`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: username, 
                    email: email,
                    password: password,                   
                })
            })    
            var data = await response.json()
            if(data.code != 200) {
                Swal.fire({
                    icon: 'info',
                    title: `<h6>(${data.code}) - ${data.message}</h6>`,
                    text: '',
                    showConfirmButton: true,
                })
            } else {
                Swal.fire({
                    icon: 'info',
                    title: `<h6>${data.message}</h6>`,
                    text: '',
                    showConfirmButton: true,
                })
                location.href = baseUrl
            }
        } catch(_) {
            Swal.fire({
                icon: 'info',
                title: `<h6>There was problem</h6>`,
                text: '',
                showConfirmButton: true,
                preConfirm: (_) => {
                    location.reload()
                },
            })
        } finally {
            $(this).text("Register")
        }
    })

    $("#logout-btn").click(function(e) {
        e.preventDefault()
        Swal.fire({
            icon: 'warning',
            title: '<h6>Are you want logout?</h6>',
            text: '',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Logout',
            showLoaderOnConfirm: true,
            reverseButtons: true,
            preConfirm: (login) => {
                return fetch('<?= base_url('/auth/logout') ?>', {
                    method: 'GET',
                }).then((res) => {
                    location.href = `<?= base_url() ?>`
                }).catch((_) => {})
            },
            allowOutsideClick: () => !Swal.isLoading()
        })
    })

    function productsInstance() {
        return {
            querySearchTitle: "",
            loading: false,
            loadingMore: false,
            page: 1,
            hasNext: false,
            products: [],
            async getProducts() {
                this.loading = true
                try {
                    var res = await fetch(`<?= base_url() ?>/products/init-products?page=${this.page}`)
                    var data = await res.json()
                    if(data.code == 200) {
                        this.products = data.data
                        this.hasNext = data.hasNext
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>There was problem</h6>`,
                            text: '',
                            showConfirmButton: true,
                            preConfirm: (_) => {
                                location.reload()
                            },
                        })
                    }
                }
                catch(_) {
                    Swal.fire({
                        icon: 'info',
                        title: `<h6>There was problem</h6>`,
                        text: '',
                        showConfirmButton: true,
                        preConfirm: (_) => {
                            location.reload()
                        },
                    })
                } finally {
                    this.loading = false
                }
            },
            filteredProducts() {
                this.loading = true
                fetch(`<?= base_url() ?>/products/init-products?page=${this.page}&title=${this.querySearchTitle}`)
                .then(res => res.json())
                .then(data => {
                    this.products = data.data
                    this.hasNext = data.hasNext
                    this.loading = false
                })
             },
            productDetail(slug) {
                location.href = `<?= base_url() ?>/products/${slug}`
            },
            async loadMoreProducts() {
                if(this.hasNext) {
                    this.page += 1
                    this.loadingMore = true
                    try {
                        var res = await fetch(`<?= base_url() ?>/products/init-products?page=${this.page}`)
                        var data = await res.json()
                        if(data.code == 200) {
                            this.products = this.products.concat(data.data);
                            this.hasNext = data.hasNext
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: `<h6>There was problem</h6>`,
                                text: '',
                                showConfirmButton: true,
                                preConfirm: (_) => {
                                    location.reload()
                                },
                            })
                        }
                    } catch(_) {
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>There was problem</h6>`,
                            text: '',
                            showConfirmButton: true,
                            preConfirm: (_) => {
                                location.reload()
                            },
                        })
                    } finally {
                        this.loadingMore = false
                    }
                } 
            }
        }
    }

    var mainListDiv = document.getElementById("mainListDiv");
    mediaButton = document.getElementById('mediaButton');

    mediaButton.onclick = function() {
      mainListDiv.classList.toggle('show_list');
      mediaButton.classList.toggle('active');
    }

    anime({
      targets: '.ani_image',
      translateX: 70,
      loop: true,
      direction: 'alternate',
      easing: 'easeInOutSine'
    })

    $('.owl-carousel').owlCarousel({
      loop: true,
      margin: 10,
      nav: true,
      dots: false,
      navText: ["<i class='far fa-chevron-left'></i>", "<i class='far fa-chevron-right'></i>"],
      responsive: {
        0: {
          items: 1
        },
        768: {
          items: 1
        },
        1000: {
          items: 1
        }
      }
    })
    
</script>