<div class="row">

    <div class="col-md-10 col-md-offset-1">
        <?= $this->draw('account/menu') ?>
        <h1>Flickr</h1>
    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
            <?php
                if (empty(\Idno\Core\Idno::site()->session()->currentUser()->flickr)) {
                    ?>
                    <div class="control-group">
                        <div class="controls-config">
                            <div class="row">
                                <div class="col-md-7">
                                    <p>
                                        Easily share pictures to Flickr.</p>

                                    <p>
                                        With Flickr connected, you can cross-post images that you publish publicly on
                                        your site.
                                    </p>

                            <div class="social">
                                <p>
                                    <a href="<?= $vars['login_url'] ?>" class="connect fl"><i class="fa fa-flickr"></i> Connect Flickr</a>
                                </p>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                <?php

                } else if (!\Idno\Core\Idno::site()->config()->multipleSyndicationAccounts()) {

                    ?>
                    <div class="control-group">
                        <div class="controls-config">
                            <div class="row">
                                <div class="col-md-7">
                                    <p>
                                        Your account is currently connected to Flickr. Public content that you post here
                                        can be shared with your Flickr account.
                                    </p>

									<div class="social">
										<form action="<?=\Idno\Core\Idno::site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
										<p>
                                        <input type="hidden" name="remove" value="1"/>
                                        <button type="submit" class="connect fl connected"><i class="fa fa-flickr"></i> Disconnect Flickr</button>
                                        <?= \Idno\Core\Idno::site()->actions()->signForm('/flickr/deauth/') ?>
                                    	</p>
                                		</form>
                            		</div>
                        		</div>
                    		</div>
                        </div>
                    </div>

                <?php

                } else {

                    ?>
                    <div class="control-group">
                        <div class="controls-config">
                            <div class="row">
                                <div class="col-md-7">
                                    <p>
                                        You have connected the following accounts to Flickr. Public content that you
                                        post here
                                        can be shared with your Flickr accounts.
                                    </p>
                            <?php

                                if ($accounts = \Idno\Core\Idno::site()->syndication()->getServiceAccounts('flickr')) {

                                    foreach ($accounts as $account) {

                                        ?>
									<div class="social">
                                        <form action="<?=\Idno\Core\Idno::site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
                                            <p>
                                                <input type="hidden" name="remove" value="<?= $account['username'] ?>"/>
                                                <button type="submit"
                                                        class="connect fl connected"><i class="fa fa-flickr"></i> <?= $account['username'] ?> (Disconnect)</button>
                                                <?= \Idno\Core\Idno::site()->actions()->signForm('/flickr/deauth/') ?>
                                            </p>
                                        </form>
                                        </div>
                                    <?php

                                    }

                                } else {

                                    ?>

                                    <div class="social">
                                        <form action="<?=\Idno\Core\Idno::site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
                                            <p>
                                                <input type="hidden" name="remove" value="1"/>
                                                <button type="submit" class="connect fl connected"><i class="fa fa-flickr"></i> Disconnect Flickr</button>
                                                <?= \Idno\Core\Idno::site()->actions()->signForm('/flickr/deauth/') ?>
                                            </p>
                                        </form>
                                        </div>
                                    <?php

                                }

                            ?>

                          <p>
                                        <a href="<?= $vars['login_url'] ?>" class=""><i class="fa fa-plus"></i> Add another Flickr account</a>
                                    </p>
                        </div>
                    </div>
                        </div>
                    </div>
                <?php

                }
            ?>
    </div>
</div>
