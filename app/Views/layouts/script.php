
<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous">
</script>

<!-- Toast Jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

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
    function register() {
        RegForm.style.transform = "translatex(0px)"
        LoginForm.style.transform = "translatex(0px)"
        Indicator.style.transform = "translateX(100px)"
    }
    function login() {
        RegForm.style.transform = "translatex(300px)"
        LoginForm.style.transform = "translatex(300px)"
        Indicator.style.transform = "translate(0px)"
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
           console.log(await response.json())
           $(this).text("Register")
        } catch(e) {
            console.log(e)
        }
    })
    


</script>