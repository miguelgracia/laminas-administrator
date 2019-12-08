<?php

namespace Administrator\Traits;

trait DeleteAction
{
    /**
     * @return Json
     */
    public function deleteAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->tableGateway->delete(['id' => $this->params()->fromRoute('id')]);

            $response = $this->getResponse();
            $response->setContent(json_encode([
                'status' => 'ok',
                'message' => 'Registro ELIMINADO'
            ]));
            return $response;
        }

        return $this->goToSection('login');
    }
}
