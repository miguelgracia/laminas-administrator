<?php

namespace Administrator\Traits;

use Administrator\Service\DatatableConfigInterface;
use Laminas\View\Model\ViewModel;

trait IndexAction
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $this->datatableService->setConfig(
            $this->datatableConfigService->getQueryConfig() +
            $this->datatableConfigService->getDatatableConfig(),
            $this->request->getPost('columns', []),
            $this->request->getPost('order', [])
        );

        if ($this->request->isPost()) {
            $this->datatableService->setPagination(
                $this->request->getPost('start', 1),
                $this->request->getPost('length', 1)
            );

            return $this->getResponse()->setContent(json_encode($this->datatableService->getData()));
        }

        $viewParams = [
            'settings' => [
                'headers' => $this->datatableService->getHeaderFields()
            ]
        ] + $this->datatableConfigService->getViewParams();

        return (new ViewModel($viewParams))->setTemplate('administrator/list-datatable');
    }
}
