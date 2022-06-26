<?php 
    use Config\Services;

    $request = Services::request();
    $session = Services::session();

    $segment = $request->uri->getSegment(1);
?>

<!-- Alpine JS -->
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

<script>
    var MenuItems = document.getElementById("MenuItems");
    MenuItems.style.maxHeight = "0px";
    function menutoggle() {
        if (MenuItems.style.maxHeight == "0px") {
            MenuItems.style.maxHeight = "200px"
        }
        else {
            MenuItems.style.maxHeight = "0px"
        }
    }
    var LoginForm = document.getElementById("LoginForm")
    var RegForm = document.getElementById("RegForm")
    var Indicator = document.getElementById("Indicator")
    
    <?php if($segment == "auth") { ?>
        Indicator.style.transform = "translate(0px)"
        LoginForm.style.transform = "translatex(300px)"
        RegForm.style.transform = "translatex(300px)"
    <?php } ?>
    function register() {
        RegForm.style.transform = "translatex(0px)"
        LoginForm.style.transform = "translatex(0px)"
        Indicator.style.transform = "translatex(100px)"
    }
    function login() {
        RegForm.style.transform = "translatex(300px)"
        LoginForm.style.transform = "translatex(300px)"
        Indicator.style.transform = "translate(0px)"
    }

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
    // var SmallImg = $(".small-img")
    
    // if(SmallImg.length != 0) {
    //     SmallImg[0].onclick = function () {
    //         ProductImg.src = SmallImg[0].src
    //     }
    //     SmallImg[1].onclick = function () {
    //         ProductImg.src = SmallImg[1].src
    //     }
    //     SmallImg[2].onclick = function () {
    //         ProductImg.src = SmallImg[2].src
    //     }
    //     SmallImg[3].onclick = function () {
    //         ProductImg.src = SmallImg[3].src
    //     }
    //     SmallImg[4].onclick = function () {
    //         ProductImg.src = SmallImg[4].src
    //     }
    // }

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
            $(this).text("Register")
        } catch(_) {
            Swal.fire({
                icon: 'info',
                title: `<h6>There was problem</h6>`,
                text: '',
                showConfirmButton: true,
            })
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
            $(this).text("Register")
        } catch(_) {
            Swal.fire({
                icon: 'info',
                title: `<h6>There was problem</h6>`,
                text: '',
                showConfirmButton: true,
            })
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
            search: "",
            loading: false,
            page: 1,
            hasNext: false,
            products: [],
            getProducts() {
                this.loading = true
                fetch(`<?= base_url() ?>/products/init-products?page=${this.page}`)
                .then((response) => response.json())
                .then((json) => {
                    var res = json
                    if(res.code == 200) {
                        this.products = res.data
                        this.hasNext = res.hasNext
                        this.loading = false
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: `<h6>There was problem</h6>`,
                            text: '',
                            showConfirmButton: true,
                        })
                    }
                })
                .catch((_) => {})
            },
            productDetail(slug) {
                location.href = `<?= base_url() ?>/products/${slug}`
            },
            loadMoreProducts() {
                if(this.hasNext) {
                    this.page += 1
                    fetch(`<?= base_url() ?>/products/init-products?page=${this.page}`)
                    .then((response) => response.json())
                    .then((json) => {
                        var res = json
                        if(res.code == 200) {
                            this.products = this.products.concat(res.data);
                            this.hasNext = res.hasNext
                            this.loading = false
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: `<h6>There was problem</h6>`,
                                text: '',
                                showConfirmButton: true,
                            })
                        }
                    }).catch((_) => {})
                } 
            }
        }
    }
    

</script>