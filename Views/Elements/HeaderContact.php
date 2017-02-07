<div class="container-fluid">
    <div class="row">
        <div class="headerAuthorized">

            <div class="col-sm-4 col-md-7">
                <a class="wise" href="#"></a>
            </div>

            <div class="col-sm-8 col-md-5">
                <div class="blockButtons logout" id="blockButtons">
                    <ul class="menu">
                        <li>
                            <div class="linkStyle">
                                <a href="/contact/showlist"><img src="/Public/Images/home.png"><span>Home</span></a>
                            </div>
                            <ul class="submenu">
                                <li>
                                    <div class="linkStyle">
                                        <a href="/contact/record"><span>Add</span></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="linkStyle">
                                        <a href="/contact/emails"><span>Event</span></a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="logged">
                        <span>Logged as:</span> <?= ucfirst($userObject->getAttribute('login')); ?>
                    </div>
                    <div class="linkStyle">
                        <a href="/contact/logout"><img src="/Public/Images/logoff.png"><span>Logout</span></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>