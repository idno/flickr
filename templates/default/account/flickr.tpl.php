<div class="row">

    <div class="span10 offset1">
        <h1>Flickr</h1>
        <?= $this->draw('account/menu') ?>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="/account/flickr/" class="form-horizontal" method="post">
            <?php
                if (empty(\Idno\Core\site()->session()->currentUser()->flickr)) {
                    ?>
                    <div class="control-group">
                        <div class="controls">
                            <p>
                                If you have a Flickr account, you may connect it here. Public content that you
                                post to this site will be automatically cross-posted to your Flickr wall.
                            </p>
                            <?php

                                if (empty($vars['login_url'])) {
                                    if (\Idno\Core\site()->session()->isAdmin()) {

                                        ?>
                                        <p>
                                            Before you can connect to Flickr, you need to set up your API details.
                                            <a href="<?= \Idno\Core\site()->config()->getDisplayURL() ?>admin/flickr/">Click
                                                here to get started.</a>
                                        </p>
                                    <?php

                                    } else {

                                        ?>
                                        <p>
                                            You can't connect to Flickr right now. Please try again later.
                                        </p>
                                    <?php
                                    }
                                } else {

                                    ?>
                                    <p>
                                        <a href="<?= $vars['login_url'] ?>" class="btn btn-large btn-success">Click here
                                            to connect Flickr to your account</a>
                                    </p>
                                <?php

                                }


                            ?>
                        </div>
                    </div>
                <?php

                } else {

                    ?>
                    <div class="control-group">
                        <div class="controls">
                            <p>
                                Your account is currently connected to Flickr. Public content that you post here
                                will be shared with your Flickr account.
                            </p>

                            <p>
                                <input type="hidden" name="remove" value="1"/>
                                <button type="submit" class="btn btn-large btn-primary">Click here to remove Flickr from
                                    your account.
                                </button>
                            </p>
                        </div>
                    </div>

                <?php

                }
            ?>
            <?= \Idno\Core\site()->actions()->signForm('/account/flickr/') ?>
        </form>
    </div>
</div>
