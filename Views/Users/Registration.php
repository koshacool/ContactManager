<div class="block">
    <div class="authorizationForm">

        <div class="container-fluid">
            <div class="row">
                <form action="/users/registration" method="post" class="registration">

                    <div class="text">Registration</div>


                    <div class="col-xs-12 col-sm-12 col-sm-offset-3 col-md-12">
                        <span class='errorMessage' id="loginMessage">
                            <?= $modelObject->errorMessages['login']; ?>
                        </span>
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 registrationText">
                        <span class='login'>Login:</span>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-7">
                        <input type="text" name='login' id="login"
                               class="forValidation"
                               placeholder="enter your login"
                               value=<?= $modelObject->getAttribute('login'); ?>>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-sm-offset-3 col-md-12">
                        <span class='errorMessage' id="passwordMessage">
                            <?= $modelObject->errorMessages['password']; ?>
                        </span>
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 registrationText">
                        <span class='password '>Password:</span>
                    </div>

                    <div class="col-xs-12 col-sm-9 col-md-7">
                        <input type="password" name='password' id="password"
                               class="forValidation"
                               placeholder="enter your password">
                    </div>

                    <div class="col-xs-12 col-sm-12 col-sm-offset-3 col-md-12">
                        <span class='errorMessage' id="repeatPasswordMessage">
                            <?= $modelObject->errorMessages['repeatPassword']; ?>
                        </span>
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 registrationText">
                        <span class='password'>Repeat password:</span>
                    </div>
                    <div class="col-xs-12 col-sm-9 col-md-7">
                        <input type="password" name='repeatPassword'
                               class="forValidation"
                               id="repeatPassword"
                               placeholder="repeat password">
                    </div>

                    <div>
                        <button class="buttonStyle" type="submit">
                            <img src="/Public/Images/login.png">
                            <span>Registraion</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>