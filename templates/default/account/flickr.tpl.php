<?php

    /**
     * Flickr pages
     *
     * PHP version 5.6
     *
     * @category Plug-in
     * @package  Photo_POSSE
     * @author   Known, Inc <hello@withknown.com>
     * @license  https://github.com/idno/Known/blob/master/LICENSE Apache
     * @link     https://github.com/Idno/Flickr
     */

?><div class="row">

    <div class="col-md-10 col-md-offset-1">
        <?php echo $this->draw('account/menu') ?>
        <h1><?php echo \Idno\Core\Idno::site()->language()->_('Flickr'); ?></h1>
    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
            <?php

            if (!empty(\Idno\Core\site()->config()->flickr['apiKey']) && !empty(\Idno\Core\site()->config()->flickr['secret'])) {


            if (empty(\Idno\Core\Idno::site()->session()->currentUser()->flickr)) {
                ?>
                    <div class="control-group">
                        <div class="controls-config">
                            <div class="row">
                                <div class="col-md-7">
                                    <p>
                                    <?php echo \Idno\Core\Idno::site()->language()->_('Easily share pictures to Flickr.'); ?></p>

                                    <p>
                                    <?php echo \Idno\Core\Idno::site()->language()->_('With Flickr connected, you can cross-post images that you publish publicly on your site.'); ?>
                                    </p>

                            <div class="social">
                                <p>
                                    <a href="<?php echo $vars['login_url'] ?>" class="connect fl"><i class="fab fa-flickr"></i> <?php echo \Idno\Core\Idno::site()->language()->_('Connect Flickr'); ?></a>
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
                                    <?php echo \Idno\Core\Idno::site()->language()->_('Your account is currently connected to Flickr. Public content that you post here can be shared with your Flickr account.'); ?>
                                    </p>

                                    <div class="social">
                                        <form action="<?php echo \Idno\Core\Idno::site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
                                        <p>
                                        <input type="hidden" name="remove" value="1"/>
                                        <button type="submit" class="connect fl connected"><i class="fab fa-flickr"></i> <?php echo \Idno\Core\Idno::site()->language()->_('Disconnect Flickr'); ?></button>
                                    <?php echo \Idno\Core\Idno::site()->actions()->signForm('/flickr/deauth/') ?>
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
                                    <?php echo \Idno\Core\Idno::site()->language()->_('You have connected the following accounts to Flickr. Public content that you post here can be shared with your Flickr accounts.'); ?>
                                    </p>
                        <?php

                        if ($accounts = \Idno\Core\Idno::site()->syndication()->getServiceAccounts('flickr')) {

                            foreach ($accounts as $account) {

                                ?>
                                    <div class="social">
                                        <form action="<?php echo \Idno\Core\Idno::site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
                                            <p>
                                                <input type="hidden" name="remove" value="<?php echo $account['username'] ?>"/>
                                                <button type="submit"
                                                        class="connect fl connected"><i class="fab fa-flickr"></i> <?php echo $account['username'] ?> (<?php echo \Idno\Core\Idno::site()->language()->_('Disconnect'); ?>)</button>
                                        <?php echo \Idno\Core\Idno::site()->actions()->signForm('/flickr/deauth/') ?>
                                            </p>
                                        </form>
                                        </div>
                                    <?php

                            }

                        } else {

                            ?>

                                    <div class="social">
                                        <form action="<?php echo \Idno\Core\Idno::site()->config()->getDisplayURL()?>flickr/deauth" class="form-horizontal" method="post">
                                            <p>
                                                <input type="hidden" name="remove" value="1"/>
                                                <button type="submit" class="connect fl connected"><i class="fab fa-flickr"></i> <?php echo \Idno\Core\Idno::site()->language()->_('Disconnect Flickr'); ?></button>
                                        <?php echo \Idno\Core\Idno::site()->actions()->signForm('/flickr/deauth/') ?>
                                            </p>
                                        </form>
                                        </div>
                                <?php

                        }

                        ?>

                          <p>
                                        <a href="<?php echo $vars['login_url'] ?>" class=""><i class="fa fa-plus"></i> <?php echo \Idno\Core\Idno::site()->language()->_('Add another Flickr account'); ?></a>
                                    </p>
                        </div>
                    </div>
                        </div>
                    </div>
                <?php

            }

            } else {

                if (\Idno\Core\site()->session()->currentUser()->isAdmin()) {

                    ?>
                                  <div class="control-group">
                      <div class="controls-config">
	                    <div class="row">
                                 <div class="col-md-7">
                    <p>
                        <?= \Idno\Core\Idno::site()->language()->_('Before you can begin connecting to Flickr, you need to set it up.'); ?>
                    </p>
                    <p>
                        <a href="<?= \Idno\Core\site()->config()->getDisplayURL() ?>admin/flickr/"><?= \Idno\Core\Idno::site()->language()->_('Click here to begin Flickr configuration.'); ?></a>
                    </p>
                <?php

                } else {

                    ?>
                    <p>
                        <?= \Idno\Core\Idno::site()->language()->_('The administrator has not finished setting up Flickr on this site. Please come back later.'); ?>
                    </p>
                    </div>
                    </div>
                    </div>
                    </div>

                <?php

                }

            }

        ?>
    </div>
</div>
