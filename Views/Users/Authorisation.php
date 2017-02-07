<div class="block">
    <div class="authorizationForm">

        <div class="container-fluid">
            <div class="row">
                <form class='authorization' action="/users/authorisation" method="post" id="authorisation">


                    <div class="text">
                        <p>Authorisation</p>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-sm-offset-3 col-md-12">
                        <span class='errorMessage' id="loginMessage">
                            <?= $modelObject->errorMessages['login']; ?>
                        </span>
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <label class='login' for="login">Login:</label>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-7">
                        <input type="text" name='login' id="login" class="forValidation"
                               placeholder="enter your login"
                               value=<?= $modelObject->getAttribute('login'); ?>>
                    </div>


                    <div class="col-xs-12  col-sm-12 col-sm-offset-3 col-md-12">
                       <span class='errorMessage' id="passwordMessage">
                           <?= $modelObject->errorMessages['password']; ?>
                       </span>

                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <label class='password' for="password">Password:</label>
                    </div>
                    <div class="col-xs-12 col-sm-9 col-md-7">
                        <input type="password" name='password' id="password" class="forValidation"
                               placeholder="enter your password">
                    </div>


                    <div class="col-xs-10 col-sm-12 col-sm-offset-3 col-md-12 ">
                        <div class="link">
                            <a href="#">Forgot Password?</a>
                        </div>
                    </div>

                    <div class="col-xs-7  col-sm-12 col-sm-offset-3 col-md-12 ">
                        <div class="link">
                            <a href="/users/registration">Register Now!</a>
                        </div>
                    </div>

                    <div class="col-xs-12 col-xs-offset-0 col-sm-12 col-sm-offset-3 col-md-12 col-md-offset-3">
                        <button class="buttonStyle" type="submit">
                            <img src="/Public/Images/login.png">
                            <span>Login</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

