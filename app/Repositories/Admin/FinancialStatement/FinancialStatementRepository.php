<?php namespace App\Repositories\Admin\FinancialStatement;

interface FinancialStatementRepository
{
    public function getReportPaginated($request);
    public function getIncomeStatement($request);
    public function getExpenseStatement($request);
    public function getRevenue($request);
    public function Income($from, $to);
    public function Expense($from, $to);
    public function getFlagStatus();
}
