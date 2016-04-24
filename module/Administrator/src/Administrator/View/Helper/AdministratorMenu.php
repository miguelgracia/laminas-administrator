<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 1/7/2016
 * Time: 1:29 PM
 */

namespace Administrator\View\Helper;


use Zend\View\Helper\Navigation\Menu;

use RecursiveIteratorIterator;
use Zend\Navigation\AbstractContainer;
use Zend\Navigation\Page\AbstractPage;
use Zend\View;
use Zend\View\Exception;

class AdministratorMenu extends Menu
{
    protected $ulClassMenuOpen = 'menu-open';

    public function setUlClassMenuOpen($class)
    {
        $this->ulClassMenuOpen = $class;
        return $this;
    }
    /**
     * Renders a normal menu (called from {@link renderMenu()})
     *
     * DREAMSITE: Sobreescribimos la funci�n para poder a�adir una clase al ul de una subsecci�n del men�
     *
     * @param  AbstractContainer $container          container to render
     * @param  string            $ulClass            CSS class for first UL
     * @param  string            $indent             initial indentation
     * @param  int|null          $minDepth           minimum depth
     * @param  int|null          $maxDepth           maximum depth
     * @param  bool              $onlyActive         render only active branch?
     * @param  bool              $escapeLabels       Whether or not to escape the labels
     * @param  bool              $addClassToListItem Whether or not page class applied to <li> element
     * @param  string            $liActiveClass      CSS class for active LI
     * @return string
     */
    protected function renderNormalMenu(
        AbstractContainer $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth,
        $onlyActive,
        $escapeLabels,
        $addClassToListItem,
        $liActiveClass
    ) {
        $html = '';

        // find deepest active
        $found = $this->findActive($container, $minDepth, $maxDepth);
        /* @var $escaper \Zend\View\Helper\EscapeHtmlAttr */
        $escaper = $this->view->plugin('escapeHtmlAttr');

        if ($found) {
            $foundPage  = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage = null;
        }

        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
            RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }

        // iterate container
        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();
            $isActive = $page->isActive(true);
            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibility
                continue;
            } elseif ($onlyActive && !$isActive) {
                // page is not active itself, but might be in the active branch
                $accept = false;
                if ($foundPage) {
                    if ($foundPage->hasPage($page)) {
                        // accept if page is a direct child of the active page
                        $accept = true;
                    } elseif ($foundPage->getParent()->hasPage($page)) {
                        // page is a sibling of the active page...
                        if (!$foundPage->hasPages(!$this->renderInvisible) ||
                            is_int($maxDepth) && $foundDepth + 1 > $maxDepth) {
                            // accept if active page has no children, or the
                            // children are too deep to be rendered
                            $accept = true;
                        }
                    }
                }

                if (!$accept) {
                    continue;
                }
            }

            // make sure indentation is correct
            $depth -= $minDepth;
            $myIndent = $indent . str_repeat('        ', $depth);

            if ($depth > $prevDepth) {
                // start new ul tag
                if ($ulClass && $depth ==  0) {
                    $ulClass = ' class="' . $escaper($ulClass) . '"';
                } else {

                    /*
                     * buscamos la opci�n pagesWrapClass (creada por nosotros para a�adir al ul del submen�)
                     * se setea en la clase GestorNavigation
                     */

                    $wrapClass = $page->get('pagesWrapClass') ?: "";
                    if ($isActive) {
                        $wrapClass .= ' '.$this->ulClassMenuOpen;
                    }
                    $ulClass = ' class="' . $escaper($wrapClass) . '"';
                }
                $html .= $myIndent . '<ul' . $ulClass . '>' . PHP_EOL;
            } elseif ($prevDepth > $depth) {
                // close li/ul tags until we're at current depth
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $ind = $indent . str_repeat('        ', $i);
                    $html .= $ind . '    </li>' . PHP_EOL;
                    $html .= $ind . '</ul>' . PHP_EOL;
                }
                // close previous li tag
                $html .= $myIndent . '    </li>' . PHP_EOL;
            } else {
                // close previous li tag
                $html .= $myIndent . '    </li>' . PHP_EOL;
            }

            // render li tag and page
            $liClasses = array();
            // Is page active?
            if ($isActive) {
                $liClasses[] = $liActiveClass;
            }
            // Add CSS class from page to <li>
            if ($addClassToListItem && $page->getClass()) {
                $liClasses[] = $page->getClass();
            }
            $liClass = empty($liClasses) ? '' : ' class="' . $escaper(implode(' ', $liClasses)) . '"';

            $html .= $myIndent . '    <li' . $liClass . '>' . PHP_EOL
                . $myIndent . '        ' . $this->htmlify($page, $escapeLabels, $addClassToListItem) . PHP_EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth+1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat('        ', $i-1);
                $html .= $myIndent . '    </li>' . PHP_EOL
                    . $myIndent . '</ul>' . PHP_EOL;
            }
            $html = rtrim($html, PHP_EOL);
        }

        return $html;
    }
}