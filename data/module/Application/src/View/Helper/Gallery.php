<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Paginator\Paginator;

class Gallery extends AbstractHelper
{
    protected $lang;

    protected $withBorder = false;

    public function withBorder()
    {
        $this->withBorder = true;
        return $this;
    }

    public function withoutBorder()
    {
        $this->withBorder = false;
        return $this;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function getContentWrapper($type = 'job')
    {
        return
            "<div class='col-md-12 list-item '. $type>
                <div class='row'>
                    <div class='col-md-7'><img class='img-responsive' src='%s' /></div>
                    <div class='col-md-5'>
                        <h3><a href='%s'>%s</a></h3>
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

    private function getSeparator()
    {
        return "";
//        return "<div class='clearfix visible-sm-block'></div>";
    }

    private function getGalleryWrapper()
    {
        return "<div class='col-md-4 col-sm-6 col-xxs-12'>
            %s
            <div class='content hide'>%s</div>
            </div>";
    }

    private function getImageWrapper()
    {
        return "<a data-fslightbox='%s' href='%s' class='abstpl-project-item %s'>
                    <img src='%s' alt='Image' class='img-responsive'>
                    <div class='abstpl-text'>
                        <h2>%s</h2>
                        <span>%s</span>
                    </div>
                </a>";
    }

    public function renderFeatured($elements, $galleryPrefix = 'gallery')
    {
        $galleryHtml = '';

        foreach ($elements as $index => $element) {

            $imagesHtml = '';

            $images = json_decode($element->imageUrl);

            $galleryWrapper = $this->getGalleryWrapper();

            foreach ($images as $indexImg => $img) {
                $classes = $this->withBorder ? 'with-border' : '';
                if ($indexImg > 0) {
                    $classes .= ' hide';
                }
                $imagesHtml .= sprintf(
                    $this->getImageWrapper(),
                    $galleryPrefix . $index,
                    $img,
                    $classes,
                    $img,
                    $element->title,
                    $element->categoryTitle
                );
            }

            $galleryHtml .= sprintf($galleryWrapper, $imagesHtml, $element->content);

            if (($index + 1) % 3 === 0) {
                $galleryHtml .= $this->getSeparator();
            }
        }

        echo $galleryHtml;

        $this->withBorder = false;
    }

    public function render($lang, $jobs)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        if ($jobs instanceof Paginator) {
            foreach ($jobs as $index => $job) {
                $job->imageUrl = json_decode($job->imageUrl);
                $linkUrl = $url('locale/jobs/category/detail', [
                    'locale' => $lang,
                    'category' => $job->categoryUrlKey,
                    'detail' => $job->urlKey
                ]);

                $html .= sprintf(
                    $this->getContentWrapper(),
                    (is_array($job->imageUrl) ? $job->imageUrl[0] : $job->imageUrl),
                    $linkUrl,
                    $job->title,
                    $job->content,
                    $url('locale/jobs/category', [
                        'locale' => $lang,
                        'category' => $job->categoryUrlKey
                    ]),
                    $job->categoryTitle,
                    $linkUrl,
                    $this->translator->translate('Show work', 'frontend')
                );
            }
        }

        echo $html;
    }
}
