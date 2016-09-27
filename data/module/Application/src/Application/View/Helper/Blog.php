<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Paginator\Paginator;

class Blog extends AbstractHelper
{
    function __invoke()
    {
        return $this;
    }

    public function getContentWrapper()
    {
        return "<div class='col-md-12 list-item blog'>
                        <div class='row'>
                            <div class='col-md-7'><img class='img-responsive' src='%s' /></div>
                            <div class='col-md-5'>
                                <h3>%s</h3>
                                %s
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-9'>
                                <span>
                                    <i class='fa fa-tag'></i>
                                    <a href='%s'>%s</a>
                                </span>
                            </div>
                            <div class='col-md-3'>
                                <a href='%s' class='btn btn-default pull-right'>%s</a>
                            </div>
                        </div>
                    </div>";
    }

    public function render($lang, $blogs)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        if ($blogs instanceof Paginator) {

            foreach ($blogs as $index => $blog) {

                $blog->imageUrl = json_decode($blog->imageUrl);

                $html .= sprintf(
                    $this->getContentWrapper(),
                    (is_array($blog->imageUrl) ? $blog->imageUrl[0] : $blog->imageUrl),
                    $blog->title,
                    $blog->content,
                    $url($lang.'/blog/category',array(
                        'category' => $blog->categoryUrlKey
                    )),
                    $blog->categoryTitle,
                    $url($lang.'/blog/category/detail',array(
                        'category' => $blog->categoryUrlKey,
                        'detail' => $blog->urlKey
                    )),
                    $this->translator->translate('Read more','frontend')
                );
            }
        }

        echo $html;
    }
}