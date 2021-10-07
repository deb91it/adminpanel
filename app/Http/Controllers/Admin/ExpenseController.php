<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\Expense\ExpenseRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\ExpenseCategoryRequest;
use App\Http\Requests\Admin\ExpenseRequest;
use Excel;
class ExpenseController extends Controller
{
    protected $expense;

    public function __construct(
        ExpenseRepository $expense
    )
    {
        $this->expense = $expense;
    }

    public function index()
    {
        return view('admin.expense.index');
    }

    public function getDataTableReport(Request $request)
    {
        return $this->expense->getReportPaginated($request);
    }

    public function create()
    {
        $cat_list = $this->expense->ListOfCategories();
        $data = [
            "cat_list" => $cat_list
        ];
        return view('admin.expense.create', $data);
    }

    public function edit($id)
    {
        $expense = $this->expense->findOrThrowException($id);
        $cat_list = $this->expense->ListOfCategories();
        $data = [
            "cat_list" => $cat_list,
            "expense" => $expense,
        ];
        return view('admin.expense.edit', $data);
    }

    public function store(ExpenseRequest $request)
    {
        $expense = $this->expense->store($request);
        if ($expense > 0)
        {
            return redirect()->route('admin.expenses')->with('flashMessageSuccess','The Voucher has successfully created !');
        }
        return redirect()->route('admin.expenses')->with('flashMessageError','Unable to create Voucher');
    }

    public function update(ExpenseRequest $request, $id)
    {
        $expense = $this->expense->update($request, $id);
        if ($expense > 0)
        {
            return redirect()->route('admin.expenses')->with('flashMessageSuccess','The Voucher has successfully updated !');
        }
        return redirect()->route('admin.expenses')->with('flashMessageError','Unable to updated Voucher');
    }

    public function destroy($id)
    {
        $expense = $this->expense->delete($id);
        if ($expense > 0)
        {
            return redirect()->route('admin.expenses')->with('flashMessageSuccess','The Voucher has successfully updated !');
        }
        return redirect()->route('admin.expenses')->with('flashMessageError','Unable to updated Voucher');
    }

    public function categoryList()
    {
        $exp_cat = $this->expense->categoryList();
        $data = [
            "exp_cat" => $exp_cat
        ];
        return view('admin.expense.category.index', $data);
    }

    public function categoryCreate()
    {
        $data = [
        ];
        return view('admin.expense.category.create', $data);
    }

    public function categoryStore(ExpenseCategoryRequest $request)
    {
        $exp_cat = $this->expense->categoryStore($request);
        if ($exp_cat > 0)
        {
            return redirect()->route('admin.expense-categorys')->with('flashMessageSuccess','The Category has successfully created !');
        }
        return redirect()->route('admin.expense-categorys')->with('flashMessageError','Unable to create Category');
    }

    public function categoryEdit($id)
    {
        $exp_cat = $this->expense->categoryFindOrThrowException($id);
        $data = [
            "cat" => $exp_cat
        ];
        return view('admin.expense.category.edit', $data);
    }

    public function categoryUpdate(ExpenseCategoryRequest $request, $id)
    {
        $exp_cat = $this->expense->categoryUpdate($request, $id);
        if ($exp_cat > 0)
        {
            return redirect()->route('admin.expense-categorys')->with('flashMessageSuccess','The Category has successfully updated !');
        }
        return redirect()->route('admin.expense-categorys')->with('flashMessageError','Unable to update Category');
    }

    public function categoryDestroy($id)
    {
        $exp_cat = $this->expense->categoryDelete($id);
        if ($exp_cat > 0)
        {
            return redirect()->route('admin.expense-categorys')->with('flashMessageSuccess','The Category has successfully deleted !');
        }
        return redirect()->route('admin.expense-categorys')->with('flashMessageError','Unable to delete Category');
    }

    public function postExportFile(Request $request)
    {

        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-expense-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-expense-from-' . $start_date . '-To-' . $end_date;
        }

        $data = $this->expense->exportFile($request);

        if (empty($data)) {
            $this->response['success'] = false;
            $this->response['msg']  = "Didn't found any data !";
            $this->response['data']  = $data;
            return response($this->response,200);
        }

        return Excel::create($file_name, function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->store($export_type, 'exports/', true);
    }
}
