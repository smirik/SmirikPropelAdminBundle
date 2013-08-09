<?php

namespace Smirik\PropelAdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AdminAbstractController extends AdminAbstractConfigurationController
{

    public function indexAction($page = false)
    {
        $this->setup();
        $this->getPaginate();

        $this->page = (int) $page;
        $this->generateRoutes();

        $sort      = $this->getRequest()->query->get('sort', 'id');
        $sort_type = $this->getRequest()->query->get('sort_type', 'desc');
        $filter    = $this->getRequest()->query->get('filter', false);
        $options   = $this->getRequest()->query->get('options', false);

        if ($options) {
            $options = json_decode($options);
        }

        $collection_query = $this->get('admin.request.process.manager')->sort($this->getQuery(), $sort, $sort_type);
        $collection_query = $this->get('admin.request.process.manager')->filter($collection_query, $filter);

        $collection = $collection_query
            ->paginate($this->page, $this->limit);

        $ajax = false;

        $response = array(
            'collection' => $collection,
            'page'       => $this->page,
            'limit'      => $this->limit,
            'columns'    => $this->get('admin.data.grid')->getColumns(),
            'layout'     => $this->layout,
            'actions'    => $this->get('admin.data.grid')->getActions(),
            'routes'     => $this->routes,
            'grid'       => $this->get('admin.data.grid'),
            'name'       => $this->name,
            'ajax'       => $ajax,
            'sort'       => $sort,
            'sort_type'  => $sort_type,
            'filter'     => json_encode($filter),
            'filter_raw' => $filter,
            'options'    => $options,
            'nativeActions' => $this->get('admin.data.grid')->getNativeActions()
        );

        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->render($this->get('admin.data.grid')->template('index_content'), $response);
        }

        return $this->render($this->get('admin.data.grid')->template('index'), $response);
    }

    public function editAction($id)
    {
        $this->initialize();
        $this->object = $this->getQuery()->findPk($id);
        if (!$this->object) {
            throw $this->createNotFoundException('Not found');
        }

        $response = $this->updateObject('edit');
        if ($response instanceOf Response) {
            return $response;
        }

        return $this->render($this->get('admin.data.grid')->template('form.edit'), $response);
    }

    public function newAction()
    {
        $this->initialize();
        $this->object = $this->getObject();

        $response = $this->updateObject('new');
        if ($response instanceOf Response) {
            return $response;
        }

        return $this->render($this->get('admin.data.grid')->template('form.new'), $response);
    }

    private function updateObject($mode)
    {
        $request = $this->getRequest();
        $form    = $this->createForm($this->getForm(), $this->object);

        $file_columns   = $this->get('admin.data.grid')->getColumns()->getFileColumns();
        $default_values = $this->get('admin.upload_file.manager')->getDefaultValues($file_columns, $this->object);

        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->get('admin.upload_file.manager')->uploadFiles($form, $file_columns, $this->object, $default_values);
                $this->object->save();

                return $this->redirect($this->generateUrl($this->routes['index']));
            }
        }

        $response = array(
            'layout'  => $this->layout,
            'object'  => $this->object,
            'form'    => $form->createView(),
            'columns' => $this->get('admin.data.grid')->getColumns(),
            'routes'  => $this->routes,
        );

        return $response;
    }

    public function deleteAction($id = 5)
    {
        $this->initialize();
        $page = $this->getRequest()->query->get('page', 1);

        $this->object = $this->getQuery()->findPk($id);
        if (!$this->object) {
            throw $this->createNotFoundException('Not found');
        }

        $this->object->delete();

        return $this->redirect($this->generateUrl($this->routes['index'], array('page' => $page)));
    }

    public function publishAction()
    {
        $this->initialize();

        $id     = (int) $this->getRequest()->query->get('id', false);

        if (!$id) {
            return new JsonResponse(array('status' => -1));
        }
        $obj = $this->getQuery()->findPk($id);
        if (!$obj) {
            return new JsonResponse(array('status' => -2));
        }

        if ($obj->isPublished()) {
            $obj->unpublish();
        } else {
            $obj->publish();
        }
        $obj->save();

        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->setup();

            return $this->render($this->get('admin.data.grid')->template('row'), array(
                'item' => $obj,
                'grid' => $this->get('admin.data.grid'),
                'columns' => $this->get('admin.data.grid')->getColumns(),
                'actions' => $this->get('admin.data.grid')->getActions(),
                'options' => false,
            ));
        }

        return $this->redirect($this->generateUrl($this->routes['index']));
    }

    public function chainAction()
    {
        $this->initialize();

        $id     = (int) $this->getRequest()->query->get('id', false);
        $status = (int) $this->getRequest()->query->get('status', false);
        $setter = $this->getRequest()->query->get('setter', false);

        if (!$id || ($status === false)) {
            return new JsonResponse(array('status' => -1));
        }
        $obj = $this->getQuery()->findPk($id);
        if (!$obj) {
            return new JsonResponse(array('status' => -2));
        }

        $obj->$setter($status);
        $obj->save();

        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->setup();

            return $this->render($this->get('admin.data.grid')->template('row'), array(
                'item' => $obj,
                'grid' => $this->get('admin.data.grid'),
                'columns' => $this->get('admin.data.grid')->getColumns(),
                'actions' => $this->get('admin.data.grid')->getActions(),
                'options' => false,
            ));
        }

        return $this->redirect($this->generateUrl($this->routes['index']));
    }

}
