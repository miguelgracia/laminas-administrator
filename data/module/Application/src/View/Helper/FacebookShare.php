<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class FacebookShare extends AbstractHelper
{
    public function render()
    {
        echo '<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, \'script\', \'facebook-jssdk\'));</script>

	<!-- Your share button code -->
	<div class="fb-share-button" data-layout="button" data-size="large"></div>';
    }
}
