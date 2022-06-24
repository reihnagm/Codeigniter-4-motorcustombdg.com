<!-- Account Page -->
<section class="account-page">
    <div class="container">
        <div class="row">
            <div class="col-2">
                <img src="<?= base_url() ?>/public/assets/images/motorcycle-banner.png" width="100%">
            </div>
            <div class="col-2">
                <div class="form-container">
                    <div class="form-btn">
                        <span onclick="login()">Login</span>
                        <span onclick="register()">Register</span>
                        <hr id="Indicator">
                    </div>
                    <form id="LoginForm">
                        <input id="email" type="email" placeholder="Email">
                        <input id="password" type="password" placeholder="Password">
                        <button id="post-login-btn" type="submit" class="btn">Login</button>
                        <a href="">Forget Password</a>
                    </form>

                    <form id="RegForm">
                        <input id="username" type="text" placeholder="Username">
                        <input id="email" type="email" placeholder="Email">
                        <input id="password" type="password" placeholder="Password">
                        <button id="post-register-btn" type="submit" class="btn">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>