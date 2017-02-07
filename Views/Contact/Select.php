<div class="contactsListEvent">
    <form class="eventList" name="eventList" id="eventList" method="post" action="/contact/select">
        <div class="selectButtons">
            <button class="buttonStyle" type='submit' name='accept' id='accept' value='true'>
                <span>Accept</span>
            </button>
            <div class="linkStyle">
                <a href="/contact/emails">
                    <span>Cancel</span>
                </a>
            </div>
        </div>
        <input type="hidden" name="mainSortColumn" id="mainSortColumn" value=<?= $mainSortColumn ?>>
        <input type="hidden" name="sortDirectionMainColumn" id="sortDirectionMainColumn"
               value=<?= $sortDirectionMainColumn ?>>
        <input type="hidden" name="sortDirectionSecondaryColumn" id="sortDirectionSecondaryColumn"
               value=<?= $sortDirectionSecondaryColumn ?>>
        <input type="hidden" name="currentPage" id="currentPage" value=<?= $currentPage ?>>

        <input type="hidden" name="arrayLastShownRowsOnPage" id="arrayLastShownRowsOnPage"
               value=<?= $arrayLastShownRowsOnPage ?>>
        <input type="hidden" name="emails" id="emails" value=<?= $emails ?>>
        <div class='formBackgroung' id='formBackgroung'>
            <div class="headerEventList">
                <input id="selectAll"  class="sequence" type="checkbox"
                       name="selectAll" <?= $selectAll ?>>

                <button name="last" class="sortButton" id="sortButtonLast" type="submit" value="last">
						<span id="last"
                              class=<?= ($mainSortColumn == 'last') ? 'sortColorActiveButton' : 'sortColorNotActiveButton' ?>>Last Name
					</span>
                </button>

                <button name="first" class="sortButton" id="sortButtonFirst" type="submit" value="first"
                >
				<span id="first"
                      class=<?= ($mainSortColumn == 'first') ? 'sortColorActiveButton' : 'sortColorNotActiveButton' ?>>First Name
			</span>
                </button>

                <div class="headerEmail">
                    <span>Email</span>
                </div>

                <div class="headerPhone">
                    <span>Cellular</span>
                </div>
            </div>
            <div id="list">
                <?php foreach ($modelObject->getAdditionalData('contacts') as $obj):
                    $message = '';
                    $select = '';
                    if (isset($arrForVerifySelect[$obj->getAttribute('id')])):
                        $select = "checked";
                    endif; ?>
                    <div class="list">

                        <input class="sequence checkbox" type="checkbox"
                               name=<?= $obj->getAttribute('id') ?> <?= $select ?> value=<?= $obj->getAttribute('email') ?>>
                        <div class="last"> <?= $obj->getAttribute('last') ?> </div>
                        <div class="first"> <?= $obj->getAttribute('first') ?> </div>
                        <div class="email"> <?= $obj->getAttribute('email') ?> </div>
                        <div class="phone"> <?= $obj->getRelationData('phone') ?> </div>

                    </div>
                <?php endforeach; ?>


            </div>
        </div>
        <div class="selectButtons">
            <button class="buttonStyle" type='submit' name='accept' id='accept' value='true'>
                <span>Accept</span>
            </button>
            <div class="linkStyle">
                <a href="/contact/emails">
                    <span>Cancel</span>
                </a>
            </div>
        </div>
        <!-- pagination starts here !-->
        <?php if ($numberOfPages > 1): ?>
            <div class="blockPagination" id="blockPagination">
                <div class="pagination" id="pagination">
                    <div class="prev">
                        <button class="prevButton" id="prev" type="submit" name='showPage'
                                value=<?= $currentPage - 1 ?> <?= ($currentPage == 1) ? 'disabled' : '' ?> >
                            <img src="/Public/Images/prev.png">
                            <span>previous</span>
                        </button>
                    </div>

                    <div class="numberPagesBlock" id="numberPagesBlock">
                        <span>Page:</span>
                        <?php if ($firstShowPage > 1): ?>
                            <button type='submit' name='showPage' class='pageNumber' id="1" value="1">
                                1
                            </button>
                        <?php endif; ?>

                        <?php if ($firstShowPage > NUMBER_DISPLAYED_PAGES_LINKS): ?>
                            <button type='submit' name='showPage' class="pageNumber"
                                    id="<?= ($firstShowPage - NUMBER_DISPLAYED_PAGES_LINKS) < 1 ? 1 : $firstShowPage - NUMBER_DISPLAYED_PAGES_LINKS ?>"
                                    value="<?= ($firstShowPage - NUMBER_DISPLAYED_PAGES_LINKS) < 1 ? 1 : $firstShowPage - NUMBER_DISPLAYED_PAGES_LINKS ?>">
                                ...
                            </button>
                        <?php endif; ?>

                        <?php while ($firstShowPage <= $lastShowPage): ?>

                            <button type='submit' name='showPage'
                                    class="<?= ($firstShowPage == $currentPage) ? 'activePageNumber' : 'pageNumber' ?>"
                                    id="<?= $firstShowPage ?>"
                                    value="<?= $firstShowPage ?>">
                                <?= $firstShowPage ?>
                            </button>
                            <?php $firstShowPage++; ?>
                        <?php endwhile;

                        if ($lastShowPage < $numberOfPages):
                            ?>
                            <button type='submit' name='showPage' class="pageNumber"
                                    id="<?= ($lastShowPage == $numberOfPages) ? $lastShowPage : $lastShowPage + 1 ?>"
                                    value="<?= ($lastShowPage == $numberOfPages) ? $lastShowPage : $lastShowPage + 1 ?>">
                                ...
                            </button>
                            <button type='submit' name='showPage' class="pageNumber"
                                    id="<?= $numberOfPages ?>"
                                    value="<?= $numberOfPages ?>" >
                                <?= $numberOfPages ?>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="next">
                        <button class="nextButton" id="next" type="submit" name='showPage'
                                value=<?= $currentPage + 1 ?>
                            <?= ($currentPage == $numberOfPages) ? 'disabled' : '' ?>>
                            <span>next</span>
                            <img src="/Public/Images/next.png">

                        </button>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </form>
</div>