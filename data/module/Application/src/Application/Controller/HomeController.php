<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class HomeController extends ApplicationController
{
    public function indexAction()
    {
        $this->headTitleHelper->append('Home');

        return new ViewModel(array(
            'homeModules'   => $this->api->homeModule->getData($this->lang),
            'megabanners'   => $this->api->megabanner->getData($this->lang),
            'lang'          => $this->lang,
            'menu'          => $this->menu
        ));
    }
}
