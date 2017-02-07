<?php if ($numberOfPages < 1): ?>
    <div class="container-fluid emptyList">
        <div class="row">

            <div class="col-xs-12  col-sm-12 col-md-12">
                <h2>EMPTY LIST</h2>
            </div>

            <div class="col-xs-12 col-sm-12   col-md-12 ">
                <div class="linkStyle emptyAdd">
                    <a href="/contact/record">
                        <center>Add</center>
                    </a>
                </div>
            </div>

        </div>
    </div>
<?php else: ?>
    <div class="contactsList" id="contactList">
        <form method="post" action="/contact/showlist">
            <input type="hidden" name="mainSortColumn" id="mainSortColumn" value=<?= $mainSortColumn ?>>
            <input type="hidden" name="sortDirectionMainColumn" id="sortDirectionMainColumn"
                   value=<?= $sortDirectionMainColumn ?>>
            <input type="hidden" name="sortDirectionSecondaryColumn" id="sortDirectionSecondaryColumn"
                   value=<?= $sortDirectionSecondaryColumn ?>>
            <input type="hidden" name="currentPage" id="currentPage" value=<?= $currentPage ?>>
            <div class='formBackgroung' id='formBackgroung'>

                <div class="headerContactsList">
                    <div class="sequence"></div>

                    <button name="last" class="sortButton" id="sortButtonLast" type="submit" value="last">
						<span id="last"
                              class=<?= ($mainSortColumn == 'last') ? 'sortColorActiveButton' : 'sortColorNotActiveButton' ?>>Last Name
					</span>
                    </button>

                    <button name="first" class="sortButton"
                            id="sortButtonFirst" type="submit" value="first">
				<span id="first"
                      class=<?= ($mainSortColumn == 'first') ? 'sortColorActiveButton' : 'sortColorNotActiveButton' ?>>
                    First Name
			</span>
                    </button>

                    <div class="headerEmail">
                        <span>Email</span>
                    </div>

                    <div class="headerPhone">
                        <span>Cellular</span>
                    </div>

                    <div class="actions">
                        <span>Actions</span>
                    </div>
                </div>

                <div id="list">
                    <?php foreach ($modelObject->getAdditionalData('contacts') as $obj): ; ?>
                        <div class="list">
                            <div class="sequence"><?= $offset + 1 . ".";
                                $offset++; ?>
                            </div>
                            <div class="last" id="last"><?= $obj->getAttribute('last') ?></div>
                            <div class="first" id="first"><?= $obj->getAttribute('first') ?></div>
                            <div class="email" id="email"><?= $obj->getAttribute('email') ?></div>
                            <div class="phone" id="phone"><?= $obj->getRelationData('phone') ?></div>
                            <div class="actionsBottom">
                                <div class="editButton">
                                    <div>
                                        <a href="/contact/record/<?= $obj->getRelationData('contact_id') ?>">
								<span>edit<span>
                                        </a>
                                    </div>
                                </div>

                                <div class="actionDelete">
                                    <div class="deleteButton">
                                        <div>
                                            <a href="/contact/view/<?= $obj->getRelationData('contact_id') ?>">
                                                <span>view</span>
                                            </a>
                                        </div>

                                    </div>
                                    <div class="xButton">
                                        <div>
                                            <a href="/contact/confirm/<?= $obj->getRelationData('contact_id') ?>">
                                                <span>X</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="emptyBlock"></div>
            </div>

            <!-- pagination starts here !-->
            <?php if ($numberOfPages > 1): ?>
                <div class="blockPagination" id="blockPagination">
                    <div class="pagination" id="pagination">
                        <div class="prev">
                            <button class="prevButton" id="prev" type="submit" name='showPage'
                                    value=<?= $currentPage - 1 ?> <?= ($currentPage == 1) ? 'disabled' : '' ?>>
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
                                        value="<?= $numberOfPages ?>">
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
<?php endif; ?>