<?php

namespace Application\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\Paginator\Paginator;

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

    public function renderFeatured($elements, $galleryPrefix = 'gallery')
    {
        // $separator = "<div class='clearfix visible-sm-block'></div>";
        $separator = "";

        $galleryWrapper = "
            <div class='col-md-4 col-sm-6 col-xxs-12'>
                %s
                <div class='content hide'>%s</div>
            </div>
        ";

        $imageWrapper = "<a data-fslightbox='%s' href='%s' class='abstpl-project-item %s'>
                    <img src='%s' alt='Image' class='img-responsive'>
                    <div class='abstpl-text'>
                        <h2>%s</h2>
                        <span>%s</span>
                    </div>
                </a>";

        $galleryHtml = '';

        foreach ($elements as $index => $element) {

            $imagesHtml = '';

            $images = json_decode($element->imageUrl);

            foreach ($images as $indexImg => $img) {
                $classes = $this->withBorder ? 'with-border' : '';
                if ($indexImg > 0) {
                    $classes .= ' hide';
                }
                $imagesHtml .= sprintf(
                    $imageWrapper,
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
                $galleryHtml .= $separator;
            }
        }

        echo $galleryHtml;

        $this->withBorder = false;
    }

    public function render($lang, $jobs)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        $imageWrapper = '<div>
                           <img alt="" height="300" class="img-fluid" src="%s">
                         </div>';

        $contentWrapper = '
        <div class="col-sm-5 product mb-0">
                        <div class="owl-carousel owl-theme" data-plugin-options="{\'items\': 1, \'margin\': 10}">
                            %s
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="summary entry-summary">

                            <h1 class="mb-0 font-weight-bold text-7">%s</h1>

                            <p class="mb-4">%s</p>

                            <div class="product-meta">
                                <span class="posted-in">Categoria: 
                                    <a rel="tag" href="%s">%s</a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <hr class="my-5">
                    </div>
        ';

        if ($jobs instanceof Paginator) {

            foreach ($jobs as $index => $job) {
                $job->imageUrl = json_decode($job->imageUrl);
                $linkUrl = $url('locale/accessories/category', [
                    'locale' => $lang,
                    'category' => $job->categoryUrlKey
                ]);

                $images = '';

                if (is_array($job->imageUrl)) {
                    foreach ($job->imageUrl as $imageUrl) {
                        $images .= sprintf($imageWrapper, $imageUrl);
                    }
                } else {
                    $images .= sprintf($imageWrapper, $job->imageUrl);
                }

                $html .= sprintf(
                    $contentWrapper,
                    $images,
                    $job->title,
                    $job->content,
                    $linkUrl,
                    $job->categoryTitle
                    /*$linkUrl,
                    $this->translator->translate('Show work', 'frontend')*/
                );
            }
        }

        echo $html;
    }
}
