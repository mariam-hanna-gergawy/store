<?php

/**
 * Books Controller function name concatinated with http request method
 * ex. create_post function: create and metod: POST
 *
 * @author Mariam
 */
require_once 'models/Book.php';

class BooksController extends Controller {

    /**
     * get books match certain search
     */
    public function index_get() {
        $model = new Book();
        $filters = array(
            "name" => FILTER_SANITIZE_STRING,
            "isbn" => FILTER_SANITIZE_STRING,
            "author" => FILTER_SANITIZE_STRING,
        );
        $searchCriteria = $this->request->get($filters, FALSE);
        $this->response->success($model->all($searchCriteria));
    }

    /**
     * store new book in db
     */
    public function create_post() {
        $data = $this->request->post();
        $model = new Book();
        if ($model->validate($data, $model->getRecordValidationRules())) {
            $model->create($data);
            $this->response->success();
        }

        $this->response->error($model->errorMessage, $model->getErrors());
    }

    /**
     * retrieve certain book
     * @param int $id
     */
    public function view_get($id) {
        $model = new Book();
        $resp = $model->find($id);
        if ($resp) {
            $this->response->success($resp);
        }
        $this->response->error($model->errorMessage);
    }

    /**
     * update certain book
     * @param int $id
     */
    public function update_post($id) {
        $data = $this->request->post();
        $model = new Book();
        $record = $model->find($id);
        if (!$record) {
            $this->response->error($model->errorMessage);
        }
        if ($model->validate($data, $model->getRecordValidationRules($id))) {
            $model->update($id, $data);
            $this->response->success();
        }

        $this->response->error($model->errorMessage, $model->getErrors());
    }

    /**
     * delete model record
     * @param int $id
     */
    public function destroy_delete($id) {
        $model = new Book();
        $record = $model->find($id);
        if (!$record) {
            $this->response->error($model->errorMessage);
        }
        $model->delete($id);
        $this->response->success();
    }

}
