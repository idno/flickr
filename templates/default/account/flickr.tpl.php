<div class="row">

    <div class="span10 offset1">
        <?= $this->draw('account/menu') ?>
        <h1>Flickr</h1>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
            <?php
                if (empty(\Idno\Core\site()->session()->currentUser()->flickr)) {
                    ?>
                    <div class="control-group">
                        <div class="controls-config">
                            <div class="row">
                                <div class="span6">
                                    <p>
                                        Easily share pictures to Flickr.</p>

                                    <p>
                                        With Flickr connected, you can cross-post images that you publish publicly on
                                        your site.
                                    </p>
                                </div>
                            </div>
                            <div class="social span6">
                                <p>
                                    <a href="<?= $vars['login_url'] ?>" class="connect fl">Connect Flickr</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php

                } else if (!\Idno\Core\site()->config()->multipleSyndicationAccounts()) {

                    ?>
                    <div class="control-group">
                        <div class="controls-config">
                            <div class="row">
                                <div class="span6">
                                    <p>
                                        Your account is currently connected to Flickr. Public content that you post here
                                        can be shared with your Flickr account.
                                    </p>
                                </div>
                            </div>
                            <div class="social">
                                <form action="<?=\Idno\Core\site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
                                    <p>
                                        <input type="hidden" name="remove" value="1"/>
                                        <button type="submit" class="connect fl connected">Disconnect Flickr</button>
                                        <?= \Idno\Core\site()->actions()->signForm('/flickr/deauth/') ?>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php

                } else {

                    ?>
                    <div class="control-group">
                        <div class="controls-config">
                            <div class="row">
                                <div class="span6">
                                    <p>
                                        You have connected the following accounts to Flickr. Public content that you
                                        post here
                                        can be shared with your Flickr accounts.
                                    </p>
                            <?php

                                if ($accounts = \Idno\Core\site()->syndication()->getServiceAccounts('flickr')) {

                                    foreach ($accounts as $account) {

                                        ?>
									<div class="social">
                                        <form action="<?=\Idno\Core\site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
                                            <p>
                                                <input type="hidden" name="remove" value="<?= $account['username'] ?>"/>
                                                <button type="submit"
                                                        class="connect fl connected"><?= $account['username'] ?> (Disconnect)</button>
                                                <?= \Idno\Core\site()->actions()->signForm('/flickr/deauth/') ?>
                                            </p>
                                        </form>
                                        </div>
                                    <?php

                                    }

                                } else {

                                    ?>
									
                                    <div class="social">
                                        <form action="<?=\Idno\Core\site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
                                            <p>
                                                <input type="hidden" name="remove" value="1"/>
                                                <button type="submit" class="connect fl connected">Disconnect Flickr</button>
                                                <?= \Idno\Core\site()->actions()->signForm('/flickr/deauth/') ?>
                                            </p>
                                        </form>
                                        </div>
                                    <?php

                                }

                            ?>
                        
                            <p>
                                <a href="<?= $vars['login_url'] ?>" class=""><icon class="icon-plus"></icon> Click here
                                    to connect another Flickr account</a>
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
