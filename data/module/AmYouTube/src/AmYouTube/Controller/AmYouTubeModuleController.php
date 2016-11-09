<?php

namespace AmYouTube\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\IndexAction;
use Zend\Db\Sql\Expression;

class AmYouTubeModuleController extends AuthController
{
    use IndexAction;

    protected $form = 'AmYouTube\\Form\\YouTubeForm';

    protected $OAUTH2_CLIENT_ID = '934446495538-k83kn2hhivvdm15um1j58srr0sc0r35l.apps.googleusercontent.com';
    protected $OAUTH2_CLIENT_SECRET = 'gEwlNEz_ItJEYMUvoBQV88n-';

    public function syncAction()
    {
        $channelsResponse = false;
        $checkAuth = false;

        $viewParams = array(
            'title' => 'Sincronizar'
        );

        $youtubeService = $this->serviceLocator->get('YoutubeService');

        $client = $youtubeService->getClient();

        $youtube = $youtubeService->getYoutubeService();

        $this->sessionService->action = 'sync';

        $code = $this->params()->fromQuery('code');

        if ($code) {
            $client->authenticate($code);
            $this->sessionService->token = $client->getAccessToken();

            return $this->redirect()->toUrl($client->getRedirectUri());
        }

        if ( isset($this->sessionService->token)) {
            $client->setAccessToken($this->sessionService->token);
        }

        // Check to ensure that the access token was successfully acquired.
        $token = $client->getAccessToken();

        if ($token and !$client->isAccessTokenExpired()) {

            try {
                // Call the channels.list method to retrieve information about the
                // currently authenticated user's channel.
                $youtubeVideos = array();

                $channelsResponse = $youtube->channels->listChannels('contentDetails', array(
                    'mine' => 'true',
                ));

                foreach ($channelsResponse['items'] as $channel) {

                    $dbVideos = $this->tableGateway->select(array(
                        'channel_id' => $channel->id
                    ));

                    $dbVideos = $dbVideos->count()
                        ? $dbVideos->setFetchGroupResultSet('code')->toObjectArray()
                        : array();

                    $uploadsListId = $channel['contentDetails']['relatedPlaylists']['uploads'];
                    $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet,status', array(
                        'playlistId' => $uploadsListId,
                        'maxResults' => 50
                    ));


                    foreach ($playlistItemsResponse['items'] as $playlistItem) {

                        $snippet = $playlistItem['snippet'];

                        $status = $playlistItem['status'];
                        $videoId = $snippet->resourceId->videoId;

                        if (array_key_exists($videoId, $dbVideos)) {
                            $dbVideos[$videoId]->title = $snippet->title;
                            $dbVideos[$videoId]->description = $snippet->description;
                            $dbVideos[$videoId]->visibility = $status->privacyStatus;
                            $dbVideos[$videoId]->updated = 1;
                        } else {
                            $dbVideos[$videoId] = new \stdClass();
                            $dbVideos[$videoId]->title = $snippet->title;
                            $dbVideos[$videoId]->description = $snippet->description;
                            $dbVideos[$videoId]->visibility = $status->privacyStatus;
                            $dbVideos[$videoId]->channelId = $snippet->channelId;
                            $dbVideos[$videoId]->channelTitle = $snippet->channelTitle;
                            $dbVideos[$videoId]->insert = 1;
                        }
                    }

                    foreach ($dbVideos as $codeId => $vid) {
                        if (isset($vid->updated) and $vid->updated == 1) {
                            $this->tableGateway->update(array(
                                'title' => $vid->title,
                                'description' => $vid->description,
                                'visibility' => $vid->visibility
                            ),array(
                                'code' => $codeId
                            ));
                        } elseif(isset($vid->insert) and $vid->insert == 1) {
                            $this->tableGateway->insert(array(
                                'title' => $vid->title,
                                'description' => $vid->description,
                                'visibility' => $vid->visibility,
                                'channel_id' => $vid->channelId,
                                'channel_title' => $vid->channelTitle,
                                'code' => $codeId
                            ));
                        } else {
                            $this->tableGateway->delete(array(
                                'code' => $codeId
                            ));
                        }
                    }
                }

                $this->sessionService->token = $client->getAccessToken();

            } catch (\Google_Service_Exception $e) {
                $client->revokeToken();
                $checkAuth = true;
            } catch (\Google_Exception $e) {
                $client->revokeToken();
                $checkAuth = true;
            }
        } else {
            $state = mt_rand();
            $client->setState($state);
            $_SESSION['state'] = $state;

            $checkAuth = true;

            $viewParams['authUrl'] = $client->createAuthUrl();
        }

        if ($checkAuth) {
            $viewParams['checkAuth'] = $checkAuth;
            $viewParams['youtube'] = $youtube;
            $viewParams['channelsResponse'] = $channelsResponse;
            $viewParams['authUrl'] = $client->createAuthUrl();

            return $viewParams;
        } else {
            return $this->redirect()->toRoute('administrator', array(
                'module' => 'you-tube',
                'action' => 'index'
            ));
        }
    }

    private function manageErrors($objError)
    {
        $errors = $objError->error->errors;
        foreach ($errors as $err) {
            $this->flashMessenger()->addErrorMessage($err->reason);
        }
    }

    public function addAction()
    {
        $formService = $this->serviceLocator->get('Administrator\Service\AdministratorFormService');

        $model = $this->tableGateway->getEntityModel();

        $youtubeService = $this->serviceLocator->get('YoutubeService');
        $client = $youtubeService->getClient();
        $youtube = $youtubeService->getYoutubeService();
        $checkAuth = false;
        $authErrors = array();

        $this->sessionService->action = 'add';

        $code = $this->params()->fromQuery('code');

        if ($code) {
            /*if (strval($_SESSION['state']) !== strval($_GET['state'])) {
                die('The session state did not match.');
            }*/

            $client->authenticate($code);
            $this->sessionService->token = $client->getAccessToken();

            return $this->redirect()->toUrl($client->getRedirectUri());
        }

        if ( isset($this->sessionService->token)) {
            $client->setAccessToken($this->sessionService->token);
        }

        // Check to ensure that the access token was successfully acquired.
        $token = $client->getAccessToken();

        $viewParams = array(
            'title' => 'Nuevo'
        );

        if ($token and !$client->isAccessTokenExpired()) {

            $form = $formService
                ->setForm($this->form, $model)
                ->addFields()
                ->getForm();

            $request = $this->getRequest();

            if ($request->isPost()) {

                $post = $request->getPost();

                $isValid = $formService->resolveForm($post);

                if ($isValid) {

                    $baseFieldSet = $formService->getBaseFieldset();

                    $model = $baseFieldSet->getObjectModel();

                    $videoPath = $_SERVER['DOCUMENT_ROOT'].$model->getUpload();

                    try {
                        // Create a snippet with title, description, tags and category ID
                        // Create an asset resource and set its snippet metadata and type.
                        // This example sets the video's title, description, keyword tags, and
                        // video category.
                        $snippet = new \Google_Service_YouTube_VideoSnippet();
                        $snippet->setTitle($model->title);
                        $snippet->setDescription($model->description);

                        // Set the video's status to "public". Valid statuses are "public",
                        // "private" and "unlisted".
                        $status = new \Google_Service_YouTube_VideoStatus();
                        $status->privacyStatus = $model->getVisibility();

                        // Associate the snippet and status objects with a new video resource.
                        $video = new \Google_Service_YouTube_Video();
                        $video->setSnippet($snippet);
                        $video->setStatus($status);

                        // Specify the size of each chunk of data, in bytes. Set a higher value for
                        // reliable connection as fewer chunks lead to faster uploads. Set a lower
                        // value for better recovery on less reliable connections.
                        $chunkSizeBytes = 1 * 1024 * 1024;

                        // Setting the defer flag to true tells the client to return a request which can be called
                        // with ->execute(); instead of making the API call immediately.
                        $client->setDefer(true);

                        // Create a request for the API's videos.insert method to create and upload the video.
                        $insertRequest = $youtube->videos->insert("status,snippet", $video);

                        // Create a MediaFileUpload object for resumable uploads.
                        $media = new \Google_Http_MediaFileUpload(
                            $client,
                            $insertRequest,
                            'video/*',
                            null,
                            true,
                            $chunkSizeBytes
                        );
                        $media->setFileSize(filesize($videoPath));


                        // Read the media file and upload it chunk by chunk.
                        $status = false;
                        $handle = fopen($videoPath, "rb");
                        while (!$status && !feof($handle)) {
                            $chunk = fread($handle, $chunkSizeBytes);
                            $status = $media->nextChunk($chunk);
                        }

                        fclose($handle);

                        // If you want to make other calls after the file upload, set setDefer back to false
                        $client->setDefer(false);

                        $insertId = $formService->save();

                        $videoModel = $baseFieldSet->getObjectModel();

                        $snippet = $status['snippet'];

                        $videoModel->setChannelId($snippet->channelId);
                        $videoModel->setChannelTitle($snippet->channelTitle);
                        $videoModel->setCode($status['id']);
                        $videoModel->setId($insertId[0]);

                        $videoTableGateway = $baseFieldSet->getTableGateWay();

                        $videoTableGateway->save($videoModel,$insertId[0]);

                        return $this->goToSection($formService->getRouteParams('module'), array(
                            'action'  => 'edit',
                            'id'      => $insertId[0]
                        ));

                    } catch (\Google_Service_Exception $e) {
                        $this->manageErrors(json_decode($e->getMessage()));
                        $client->revokeToken();
                        $checkAuth = true;
                    } catch (\Google_Exception $e) {
                        $this->manageErrors(json_decode($e->getMessage()));
                        $client->revokeToken();
                        $checkAuth = true;
                    }
                }
            }

            $title = "Nuevo";

            $blocks = $this->parseTriggers();

            $viewParams['form'] = $form;
            $viewParams['title'] = $title;
            $viewParams['blocks'] = $blocks;

        } else {
            $state = mt_rand();
            $client->setState($state);
            $_SESSION['state'] = $state;
            $client->revokeToken();
            $checkAuth = true;
        }

        $viewParams['authUrl'] = $client->createAuthUrl();
        $viewParams['checkAuth'] = $checkAuth;
        $viewParams['blocks'] = $this->parseTriggers();;

        return $viewParams;
    }

    public function editAction()
    {
        $serviceLocator = $this->serviceLocator;

        $formService = $serviceLocator->get('Administrator\Service\AdministratorFormService');

        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();

        $thisModule =  $routeMatch->getParam('module');

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->goToSection($thisModule);
        }

        $youtubeService = $this->serviceLocator->get('YoutubeService');
        $client = $youtubeService->getClient();
        $youtube = $youtubeService->getYoutubeService();

        try {
            $model = $this->tableGateway->find($id);
        }catch (\Exception $ex) {
            return $this->goToSection($thisModule);
        }

        $checkAuth = false;
        $authErrors = array();

        $this->sessionService->action = 'edit';
        $this->sessionService->id = $id;

        $code = $this->params()->fromQuery('code');

        if ($code) {
            /*if (strval($_SESSION['state']) !== strval($_GET['state'])) {
                die('The session state did not match.');
            }*/

            $client->authenticate($code);
            $this->sessionService->token = $client->getAccessToken();

            return $this->redirect()->toUrl($client->getRedirectUri());
        }

        if ( isset($this->sessionService->token)) {
            $client->setAccessToken($this->sessionService->token);
        }

        // Check to ensure that the access token was successfully acquired.
        $token = $client->getAccessToken();

        $viewParams = array(
            'title' => 'Editar'
        );

        if ($token and !$client->isAccessTokenExpired()) {

            $form = $formService
                ->setForm($this->form, $model)
                ->addFields()
                ->getForm();

            $request = $this->getRequest();

            if ($request->isPost()) {

                $isValid = $formService->resolveForm($request->getPost());

                if ($isValid) {

                    try {
                        $formService->save();
                        $baseModel = $formService->getBaseFieldset()->getObjectModel();

                        $videos = $youtube->videos->listVideos('snippet,status', array('id' => $baseModel->getCode()));
                        $video = $videos[0];

                        $videoSnippet = $video['snippet'];

                        $videoSnippet->title = $baseModel->getTitle();
                        $videoSnippet->description = $baseModel->getDescription();

                        $status = new \Google_Service_YouTube_VideoStatus();
                        $status->privacyStatus = $baseModel->getVisibility();

                        $video->setStatus($status);

                        $updateResponse = $youtube->videos->update('snippet,status',$video);

                    } catch (\Google_Service_Exception $e) {
                        $this->manageErrors(json_decode($e->getMessage()));
                        $client->revokeToken();
                        return $this->goToSection($thisModule);
                        $checkAuth = true;
                    } catch (\Google_Exception $e) {
                        $this->manageErrors(json_decode($e->getMessage()));
                        $client->revokeToken();
                        return $this->goToSection($thisModule);
                        $checkAuth = true;
                    }
                }
            }

            $viewParams['title'] = 'Edición';

            $blocks = $this->parseTriggers();

            $viewParams['form'] = $form;
            $viewParams['block'] = $blocks;

            $addAction = 'add';

            $module = $this->getEvent()->getRouteMatch()->getParam('module');

            $permissions = $this->serviceLocator->get('AmProfile\Service\ProfilePermissionService');
            if ($permissions->hasModuleAccess($module, $addAction)) {
                $controller = $this->getPluginManager()->getController();

                if (method_exists($controller, $addAction .'Action')) {
                    $viewParams['add_action'] = $controller->goToSection($module, array('action' => $addAction), true);
                }
            }
        } else {
            $state = mt_rand();
            $client->setState($state);
            $_SESSION['state'] = $state;
            $client->revokeToken();
            $checkAuth = true;
        }


        $viewParams['authUrl'] = $client->createAuthUrl();
        $viewParams['checkAuth'] = $checkAuth;
        $viewParams['blocks'] = $this->parseTriggers();;

        return $viewParams;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->goToSection('login');
        }

        $youtubeService = $this->serviceLocator->get('YoutubeService');
        $client = $youtubeService->getClient();
        $youtube = $youtubeService->getYoutubeService();

        $this->sessionService->action = 'index';

        if ( isset($this->sessionService->token)) {
            $client->setAccessToken($this->sessionService->token);
        }

        // Check to ensure that the access token was successfully acquired.
        $token = $client->getAccessToken();

        $response = $this->getResponse();

        if (!$token or $client->isAccessTokenExpired()) {
            $response->setContent(json_encode(array(
                'status' => 'ko',
                'error' => array(
                    'errors' => array(
                        array(
                            "reason" => "cannot.access", //Este error no lo devuelve la api.
                            "message" => "No ha sido posible acceder a la cuenta de google. Pulse en el botón \"sincronizar\" situado en la cabecera de este listado e inténtelo de nuevo.",
                        )
                    )
                ),
                'message' => 'No ha sido posible acceder a la cuenta de google. Pulse en el botón "sincronizar" situado en la cabecera de este listado e inténtelo de nuevo.'
            )));
            return $response;
        }

        $videoId = $this->params()->fromRoute('id');
        $rowVideo = $this->tableGateway->find($videoId);
        $codeVideo = $rowVideo->code;

        try {
            $videos = $youtube->videos->listVideos('snippet,status', array('id' => $codeVideo));
            if (isset($videos[0])) {

                $youtube->videos->delete($codeVideo);

                $this->tableGateway->delete(array('id' => $videoId));

                $response->setContent(json_encode(array(
                    'status' => 'ok',
                    'message' => 'El video ha sido eliminado'
                )));

                return $response;
            } else {
                $response->setContent(json_encode(array(
                    'status' => 'ko',
                    'error' => array(
                        'errors' => array(
                            array(
                                "reason" => "fail.delete", //Este error no lo devuelve la api.
                                'message' => 'El video no existe, ha sido borrado o no tiene permisos para eliminarlo  debido a que pertenece a otro canal de YouTube.'
                            )
                        )
                    ),
                    'message' => 'El video no existe, ha sido borrado o no tiene permisos para eliminarlo  debido a que pertenece a otro canal de YouTube.'
                )));

                return $response;
            }

        } catch (\Google_Service_Exception $e) {
            $errorMessage = $this->translateMessages(json_decode($e->getMessage()));
            $arrayError = array(
                'status' => 'ko',
                'message' => json_decode($e->getMessage()),
                'error' => $errorMessage->error
            );
            $response->setContent(json_encode($arrayError));
            return $response;
        } catch (\Google_Exception $e) {
            $errorMessage = $this->translateMessages(json_decode($e->getMessage()));
            $arrayError = array(
                'status' => 'ko',
                'message' => json_decode($e->getMessage()),
                'error' => $errorMessage->error
            );
            $response->setContent(json_encode($arrayError));
            return $response;
        } catch (\Exception $e) {
            $errorMessage = $this->translateMessages(json_decode($e->getMessage()));
            $arrayError = array(
                'status' => 'ko',
                'message' => json_decode($e->getMessage()),
                'error' => $errorMessage->error
            );
            $response->setContent(json_encode($arrayError));
            return $response;
        }
    }

    private function translateMessages($messages)
    {

        foreach ($messages->error->errors as &$error) {
            $error->message = $this->serviceLocator->get('translator')->translate($error->reason . ' description');
        }
        return $messages;
    }

    public function oauthCallbackAction()
    {
        $action = $this->sessionService->action;
        $id = $this->sessionService->id;

        $redirect = $this->redirect();
        $params = $this->params();

        $routeParams = array(
            'module' => 'you-tube',
            'action' => $action
        );

        if (is_numeric($id)) {
            $routeParams['id'] = $id;
        }

        $queryParams = array(
            'state' => $params->fromQuery('state'),
            'code' => $params->fromQuery('code')
        );

        return $redirect->toRoute('administrator',$routeParams,array(
            'query' => $queryParams
        ));
    }
}