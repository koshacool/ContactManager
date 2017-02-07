<div class="contactForm">
    <div class="blockView">
        <h2 align='center'> Information </h2>
        <?php $data = $modelObject->getAdditionalData('properties');
        foreach ($data as $key => $value):
            if ($key == 'birthday') {
                if (!empty($value['day'])) {
                    $value = $value['day'] . '.' . $value['month'] . '.' . $value['year'];
                } else {
                    $value = null;
                }
            } ?>

            <div class="blockValue">
                <p class='valueName'> <?= ucfirst($key) . ': ' ?> </p>
                <p> <?= $value ?> </p>
            </div>
        <?php endforeach; ?>
        <div class="linkStyle">
            <a href="/contact/showlist">
                <span>Ok</span>
            </a>
        </div>
    </div>


</div>