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
        <?php echo $this->draw('admin/menu')?>
        <h1><?php echo \Idno\Core\Idno::site()->language()->_('Flickr configuration'); ?></h1>
    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form action="<?php echo \Idno\Core\Idno::site()->config()->getURL()?>admin/flickr/" class="form-horizontal" method="post">
            <div class="controls-group">
                <div class="controls-config">
                    <p>
                        <?php echo \Idno\Core\Idno::site()->language()->_('To begin using Flickr, <a href="http://www.flickr.com/services/apps/" target="_blank">create a new application in the Flickr apps portal</a>.'); ?></p>
                    <p>
                        <?php echo \Idno\Core\Idno::site()->language()->_('You need to edit the authentication flow and set the callback URL to:'); ?><br />
                        <input type="text" class="form-control" value="<?php echo \Idno\Core\Idno::site()->config()->url . 'flickr/callback'?>" />
                    </p>
                </div>
            </div>
            <div class="controls-group">
                    <p>
                        <?php echo \Idno\Core\Idno::site()->language()->_('Once you\'ve finished, fill in the details below. You can then <a href="%s">connect your Flickr account</a>.', [\Idno\Core\Idno::site()->config()->getURL() . "account/flickr/"]); ?>
                    </p>
                <label class="control-label" for="name">API Key</label>
                    <input type="text" id="name" placeholder="API Key" class="form-control" name="apiKey" value="<?php echo htmlspecialchars(\Idno\Core\Idno::site()->config()->flickr['apiKey'])?>" >
                <label class="control-label" for="name">App secret</label>
                    <input type="text" id="name" placeholder="App secret" class="form-control" name="secret" value="<?php echo htmlspecialchars(\Idno\Core\Idno::site()->config()->flickr['secret'])?>" >
            </div>
          <div class="controls-group">
              <p>
                        After the Flickr application is configured, site users must authenticate their Flickr account under Settings.
                    </p>

          </div>
            <div class="controls-group">
                <div class="controls-save">
                    <button type="submit" class="btn btn-primary">Save settings</button>
                </div>
            </div>
            <?php echo \Idno\Core\Idno::site()->actions()->signForm('/admin/flickr/')?>
        </form>
    </div>
</div>
