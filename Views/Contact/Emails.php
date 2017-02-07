<?php if (empty($modelObject->getAdditionalData('newEmails'))): ?>
    <div class="container-fluid event">
        <div class="row">

            <div class="col-xs-12  col-sm-12 col-md-12">
                <h3> EVENT PAGE </h3>
            </div>

            <div class="col-xs-12  col-sm-12 col-md-12  col-md-offset-1 eventMargin">
                    <span class='errorMessage' id="emailsMessage">
                        <?= $modelObject->errorMessages['emails']; ?>
                    </span>

            </div>
            <form action="/contact/emails" method="post" id="emails">
                <div class="col-xs-12  col-sm-1 col-md-1 eventMargin">
                    <label for="inputEmails">Email</label>
                </div>
                <div class="col-xs-12  col-sm-6 col-md-5 inputEmails">
                    <input type="text" name='emails' id="inputEmails" class="forValidation"
                           placeholder="enter email address" size="50"
                           value="<?= $modelObject->getAdditionalData('emails') ?>">
                </div>
                <div class="col-xs-12  col-sm-6 col-md-5 eventMargin">
                    <button type="submit" name='send' id="send" value='1' class="buttonStyle"> Send</button>
                    <button type='submit' name='selectEmails' id='selectEmails' value='true' class="buttonStyle">
                        Select Email
                    </button>
                </div>
            </form>

        </div>
    </div>
<?php else: ?>
    <div class="container-fluid saveEmails">
        <div class="row">
            <div class="col-xs-12  col-sm-12 col-md-12">
                <h3> These email addresses unsaved. Select that you want to keep. </h3>
            </div>


            <form action="/contact/save" method="post" id="emails">
                <?php foreach ($modelObject->getAdditionalData('newEmails') as $key => $value): ?>
                    <div class="col-xs-12 col-xs-offset-1 col-sm-12 col-sm-offset-5 col-md-12 col-md-offset-5">
                        <input type="checkbox" name="<?= $key ?>" value="<?= $value ?>" >
                        <span><?= $value ?></span>
                    </div>

                <?php endforeach; ?>

                <div class="col-xs-12 col-xs-offset-1 col-sm-12 col-sm-offset-5 col-md-12 col-md-offset-5 save">
                    <button type='submit' class="buttonStyle" id="saveEmails"> Save Email</button>
                </div>
            </form>

        </div>
    </div>
<?php endif; ?>