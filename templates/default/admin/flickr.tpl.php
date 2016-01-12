<div class="row">

    <div class="col-md-10 col-md-offset-1">
	            <?=$this->draw('admin/menu')?>
        <h1>Flickr configuration</h1>
    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form action="<?=\Idno\Core\Idno::site()->config()->getURL()?>admin/flickr/" class="form-horizontal" method="post">
            <div class="controls-group">
                <div class="controls-config">
                    <p>
                        To begin using Flickr, <a href="http://www.flickr.com/services/apps/" target="_blank">create a new application in
                            the Flickr apps portal</a>.</p>
                    <p>
                        You need to edit the authentication flow and set the callback URL to:<br />
                        <input type="text" class="form-control" value="<?=\Idno\Core\Idno::site()->config()->url . 'flickr/callback'?>" />
                    </p>
                </div>
            </div>
            <div class="controls-group">
	                <p>
                        Once you've finished, fill in the details below. You can then <a href="<?=\Idno\Core\Idno::site()->config()->getURL()?>account/flickr/">connect your Flickr account</a>.
                    </p>
                <label class="control-label" for="name">API Key</label>
                    <input type="text" id="name" placeholder="API Key" class="form-control" name="apiKey" value="<?=htmlspecialchars(\Idno\Core\Idno::site()->config()->flickr['apiKey'])?>" >
                <label class="control-label" for="name">App secret</label>
                    <input type="text" id="name" placeholder="App secret" class="form-control" name="secret" value="<?=htmlspecialchars(\Idno\Core\Idno::site()->config()->flickr['secret'])?>" >
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
            <?= \Idno\Core\Idno::site()->actions()->signForm('/admin/flickr/')?>
        </form>
    </div>
</div>
