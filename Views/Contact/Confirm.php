<div class='paddingBottom'>
    <div class='deleteContact'>
        <form method="post" action=<?= "/contact/delete/" . $modelObject->getAttribute('id') ?>>
            <h3> Are you sure to remove this contact? </h3>
            <div class="buttons">
                <div class="linkStyle">
                    <a href="/contact/showlist">
                        <span>No</span>
                    </a>
                </div>

                <button class="buttonStyle" type='submit' id="confirm" name='deleteContact' value='true'>
                    <span>Yes</span>
                </button>
            </div>
        </form>
    </div>
</div>