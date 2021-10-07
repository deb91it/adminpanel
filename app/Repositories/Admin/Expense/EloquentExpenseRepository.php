<?php namespace App\Repositories\Admin\Expense;

use App\DB\Admin\Expense;
use App\DB\Admin\ExpenseCategory;
use Illuminate\Support\Facades\DB;
use Datatables;

class EloquentExpenseRepository implements ExpenseRepository
{
    public function getReportPaginated($request){

        $date_range = $request->get('columns')[5]['search']['value'];
        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (date_validate($start_date)  || date_validate($end_date)) {
                $from = $start_date;
                $to = $end_date;
            }
        }
        $query = DB::table('expense as e')
            ->select(
                "e.*","ec.name as category_name"
            )
            ->join("expense_category as ec", "e.exp_category_id", "=", "ec.id")
            ->where(["e.status" => 1])
            ->groupBy('e.id')
            ->orderBy('e.id','desc');
        if (!empty($date_range))
        {
            $query = $query->whereBetween('e.expense_date', [$from, $to]);
        }
        if (!empty($request->expense_date) && empty($date_range))
        {
            $query = $query->whereBetween('e.expense_date', [$request->expense_date, $request->expense_date]);
        }
        return Datatables::of($query)
            ->filterColumn('category_name', function($query, $keyword) {
                $query->whereRaw("ec.name like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('admin.expense.edit',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Voucher"><i class="fa fa-edit"></i></a>
                    <a href="'.route('admin.expense.delete',array($user->id)).'"  class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete Voucher"><i class="fa fa-trash"></i></a>
                    ';
            })
            ->make(true);
    }


    public function store($request)
    {
        //Upload Picture
        $image_name = '';
        $image_mime = '';
        $save_path = '';
        $pic_url = '';

        if ($request->hasfile('image_name')) {
            $save_path = public_path('resources/expense_attachment/');
            $file = $request->file('image_name');
            $image_name = time().'-'.$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path . '%s', $image_name))->resize(400, 300)->save();
            $image_mime = \Image::make($save_path . $image_name)->mime();
            $pic_url = url('resources/expense_attachment/' . $image_name);
        }
        $expense = new Expense();
        $expense->exp_category_id = $request->exp_category_id;
        $expense->expense_date = $request->expense_date;
        $expense->payment_type = $request->payment_type;
        $expense->payment_date = $request->payment_date;
        $expense->amount = $request->amount;
        $expense->image_name = $image_name;
        $expense->image_url = $pic_url;
        $expense->mime_type = $image_mime;
        $expense->description = isset($request->description) ? $request->description : null;
        $expense->status = 1;
        $expense->created_at = date("Y-m-d H:i:s");
        if ($expense->save()){
            return $expense->id;
        }
        return 0;
    }

    public function findOrThrowException($id)
    {
        return DB::table("expense as e")
            ->select(
            "e.*","ec.name as category_name"
        )
            ->join("expense_category as ec", "e.exp_category_id", "=", "ec.id")
            ->where(["e.id" => $id])
            ->first();
    }

    public function update($request, $id)
    {
        $expense = Expense::find($id);
        if ($request->hasfile('image_name')) {
            $save_path = public_path('resources/expense_attachment/');
            $file = $request->file('image_name');
            $image_name = time().'-'.$file->getClientOriginalExtension();
            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path . '%s', $image_name))->resize(400, 300)->save();
            $image_mime = \Image::make($save_path . $image_name)->mime();
            $image_url = url('resources/expense_attachment/' . $image_name);
            if (file_exists($save_path.$expense->image) == true){
                unlink(public_path('resources/expense_attachment/').$expense->image_name);
            }
        }else{
            $image_name = $expense->image_name;
            $image_mime = $expense->mime_type;
            $image_url = $expense->image_url;
        }
        $expense->exp_category_id = $request->exp_category_id;
        $expense->expense_date = $request->expense_date;
        $expense->payment_type = $request->payment_type;
        $expense->payment_date = $request->payment_date;
        $expense->amount = $request->amount;
        $expense->image_name = $image_name;
        $expense->image_url = $image_url;
        $expense->mime_type = $image_mime;
        $expense->description = isset($request->description) ? $request->description : null;
        $expense->updated_at = date("Y-m-d H:i:s");
        if ($expense->save()){
            return $expense->id;
        }
        return 0;
    }

    public function delete($id)
    {
        $expense = Expense::find($id);
        $expense->status = 0;
        if ($expense->save()){
            return $expense->id;
        }
        return 0;
    }
    
    public function categoryList()
    {
        return DB::table("expense_category")
            ->get();
    }

    public function categoryStore($request)
    {
        $cat = new ExpenseCategory();
        $cat->name = $request->name;
        $cat->status = 1;
        $cat->created_at = date("Y-m-d H:i:s");
        if ($cat->save()){
            return $cat->id;
        }
        return 0;
    }

    public function categoryFindOrThrowException($id)
    {
        return DB::table("expense_category")
            ->select("id", "name")
            ->first();
    }


    public function categoryUpdate($request, $id)
    {
        $cat = ExpenseCategory::find($id);
        $cat->name = $request->name;
        $cat->updated_at = date("Y-m-d H:i:s");
        if ($cat->save()){
            return $cat->id;
        }
        return 0;
    }

    public function categoryDelete($id)
    {
        $cat = ExpenseCategory::find($id);
        $cat->status = 0;
        if ($cat->save()){
            return $cat->id;
        }
        return 0;
    }

    public function ListOfCategories()
    {
        return DB::table("expense_category")
            ->where(["status" => 1])
            ->lists("name","id");
    }

    public function exportFile($request)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        if(empty($start_date) && empty($end_date)){
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }

        DB::setFetchMode(\PDO::FETCH_ASSOC);

        $query = DB::table('expense as e')
            ->select(
                'e.expense_date', 'ec.name as expense_category',
                'e.payment_type','e.amount', 'e.description'
            )
            ->Join("expense_category as ec", "e.exp_category_id", "=", "ec.id")
            ->where(["e.status" => 1])
            ->groupBy('e.id')
            ->orderBy('e.id','desc');

        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('e.expense_date', [$start_date, $end_date]);
        } else {
            return 0;
        }
        $data = $query->get();

        return $data;
    }
}
