<div class="contactForm">
    <form name='formRecord' id='formRecord' method='post' class='regForm'
          action=<?= '/contact/record/' . $modelObject->getAttribute('id') ?>  >
        <h2 align='center'> Information </h2>
        <?php $data = $modelObject->getAdditionalData('properties');
        foreach ($data as $key => $value):
            $error = $modelObject->errorMessages[$key];
            if ($key == 'best_phone' || $key == 'radio'):
                continue;
            endif;
            if ($key == 'home' || $key == 'work' || $key == 'cell'): ?>
                <span class='errorMessage' id="<?= $key . 'Message' ?>"><?= $error ?></span>
                <div class="blockAddForm">
                    <label class="fieldName" for=<?= $key ?>> <?= ucfirst($key) ?> </label>
                    <?php if ($data['best_phone'] == $key): ?>
                        <input type='radio' name='best_phone' class='radio' checked
                               id=<?= $key ?> value=<?= $key ?>>
                    <?php elseif ($data['best_phone'] == $value): ?>
                        <input type='radio' name='best_phone' class='radio' checked
                               id=<?= $key ?> value=<?= $key ?>>
                    <?php else: ?>
                        <input type='radio' name='best_phone' class='radio' id=<?= $key ?> value=<?= $key ?>>
                    <?php endif; ?>
                    <input type='text' id='first' size='20' maxlength='30' placeholder='enter text'
                           class='inputForm forValidation'
                           name=<?= $key ?> value=<?= $value ?>>
                </div>
                <?php continue; ?>
            <?php elseif ($key == 'city'): ?>
                <span class='errorMessage' id="<?= $key . 'Message' ?>"> <?= $error ?> </span>
                <div class="blockAddForm">
                    <label class="fieldName" for=<?= $key ?>> <?= ucfirst($key) ?> </label>
                    <input type='text' id="<?= $key ?>" size='20' maxlength='30' placeholder='enter text'
                           class='inputForm forValidation'
                           name="<?= $key ?>" value='<?= $value ?>'>
                    <div class='additionalField'>
                        <select name='cities' id='cities'>
                            <option value='false' selected id="citiesDefaultSelect"> cities</option>
                            <?php foreach ($modelObject->getAdditionalData('cities') as $city): ?>
                                <option
                                    value=<?= $city->getAttribute('zip') . ':' . $city->getAttribute('city') ?>> <?= $city->getAttribute('city') ?> </option>
                            <?php endforeach; ?>
                        </select>
                        <button type='submit' value='ok' class="okButton" > ok</button>
                    </div>
                </div>
            <?php elseif ($key == 'state'): ?>
                <span class='errorMessage' id="<?= $key . 'Message' ?>"> <?= $error ?> </span>
                <div class="blockAddForm">
                    <label class="fieldName" for=<?= $key ?>> <?= ucfirst($key) ?> </label>
                    <input type='text' id='state' size='20' maxlength='30' placeholder='enter text'
                           class='inputForm forValidation'
                           name=<?= $key ?> value='<?= $value ?>'>
                    <div class='additionalField'>
                        <select name='states' id='states'>
                            <option value='false' selected> states</option>
                            <?php foreach ($modelObject->getAdditionalData('states') as $state): ?>
                                <option
                                    value=<?= $state->getAttribute('id') . ':' . $state->getAttribute('state') ?>> <?= $state->getAttribute('state') ?> </option>
                            <?php endforeach; ?>
                        </select>
                        <button type='submit' value='ok' class="okButton" > ok</button>
                    </div>
                </div>
            <?php elseif ($key == 'country'): ?>
                <span class='errorMessage' id="<?= $key . 'Message' ?>"> <?= $error ?> </span>
                <div class="blockAddForm">
                    <label class="fieldName" for=<?= $key ?>> <?= ucfirst($key) ?> </label>
                    <input type='text' id='country' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation'
                           name=<?= $key ?> value='<?= $value ?>'>
                    <div class='additionalField'>
                        <select name='countries' id='countries'>
                            <option value='false' selected> countries</option>
                            <?php foreach ($modelObject->getAdditionalData('countries') as $country): ?>
                                <option
                                    value=<?= $country->getAttribute('id') . ':' . $country->getAttribute('country') ?>> <?= $country->getAttribute('country') ?> </option>
                            <?php endforeach; ?>
                        </select>
                        <button type='submit' value='ok' class="okButton" >ok</button>
                    </div>
                </div>
            <?php elseif ($key == 'zip'): ?>
                <?php if (is_array($value)): ?>
                    <span class='errorMessage' id="<?= $key . 'Message' ?>"> <?= $error ?> </span>
                    <div class="blockAddForm" >
                        <label class="fieldName" for=<?= $key ?>> <?= ucfirst($key) ?> </label>
                        <input type='text' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation'
                               name=<?= $key ?> id=<?= $key ?> value=''>
                        <div class='additionalField' id="zipSelectBlock">
                            <select name='zipSelect' id='zipSelect'>
                                <option value='false' selected> zips</option>
                                <?php foreach ($value as $key => $value): ?>
                                    <option value=<?= $key . ':' . $value ?>> <?= $key ?> </option>
                                <?php endforeach; ?>
                            </select>
                            <button type='submit' value='ok' class="okButton" > ok</button>
                        </div>
                    </div>
                <?php else: ?>
                    <span class='errorMessage' id="<?= $key . 'Message' ?>"> <?= $error ?> </span>
                    <div class="blockAddForm" id="zipBlock">
                        <label class="fieldName" for=<?= $key ?>> <?= ucfirst($key) ?> </label>
                        <input type='text' id=<?= $key ?> size='20' maxlength='30' placeholder='enter text'
                               class='inputForm forValidation'
                               name=<?= $key ?> value=<?= $value ?> >
                    </div>
                <?php endif; ?>
            <?php elseif ($key == 'birthday'): ?>
                <span class='errorMessage' id="<?= $key . 'Message' ?>"> <?= $error ?> </span>
                <div class="blockAddForm">
                    <label class="fieldName" for=<?= $key ?>> <?= ucfirst($key) ?> </label>
                    <input type='hidden' size='20' name=<?= $key ?>>

                    <div class='date'>
                        <select name='day' id='day'>
                            <option value='false' <?= (empty($value['day'])) ? 'selected' : '' ?>> day</option>
                            <?php for ($i = 1; $i < 32; $i++): ?>
                                <option
                                    value=<?= $i ?> <?= ($value['day'] == $i) ? 'selected' : '' ?>> <?= $i ?> </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class='date'>
                        <select name='month' id='month'>
                            <option value='false' <?= (empty($value['month'])) ? 'selected' : '' ?>> month</option>
                            <?php for ($i = 1; $i < 13; $i++): ?>
                                <option
                                    value=<?= $i ?> <?= ($value['month'] == $i) ? 'selected' : '' ?>> <?= $i ?> </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class='date'>
                        <select name='year' id='year'>
                            <option value='false' <?= (empty($value['year'])) ? 'selected' : '' ?>> year</option>
                            <?php for ($i = date("Y"); $i > 1920; $i--): ?>
                                <option
                                    value=<?= $i ?> <?= ($value['year'] == $i) ? 'selected' : '' ?>> <?= $i ?> </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            <?php else: ?>
                <span class='errorMessage' id="<?= $key . 'Message' ?>"> <?= $error ?> </span>
                <div class="blockAddForm">
                    <label class="fieldName" for=<?= $key ?>> <?= ucfirst($key) ?> </label>
                    <input type='text' id='first' size='20' maxlength='30' placeholder='enter text' class='inputForm forValidation
                        ' name=<?= $key ?> value='<?= $value ?>'>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <button class="buttonStyle" type="submit" >
            <img src="/Public/Images/login.png">
            <span>Done</span>
        </button>
    </form>
</div>