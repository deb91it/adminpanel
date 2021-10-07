<?php namespace App\Repositories\Admin\Expense;

interface ExpenseRepository
{
    public function categoryList();
    public function categoryStore($request);
    public function categoryFindOrThrowException($id);
    public function categoryUpdate($request, $id);
    public function categoryDelete($id);

    public function ListOfCategories();
    public function getReportPaginated($request);
    public function store($request);
    public function findOrThrowException($id);
    public function delete($id);
    public function exportFile($request);
}
