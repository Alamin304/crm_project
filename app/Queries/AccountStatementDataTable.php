<?php

namespace App\Queries;

use App\Models\SalarySheet;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Account;
use App\Models\CashTransfer;
use App\Models\JournalVoucher;
use Carbon\Carbon;

/**
 * Class TagDataTable
 */
class AccountStatementDataTable
{

    public function get($input = [])
    {


        /** @var Account $query */
        $query = Account::with(['branch', 'receivedBy', 'from', 'to']);

        // Filter by branch_id
        if (!empty($input['branch_id'])) {
            $query->where('branch_id', $input['branch_id']);
        }

        // Filter by account_id
        if (!empty($input['account_id'])) {
            $query->where('id', $input['account_id']);
        }

        // Get the accounts
        $accounts = $query->get();

        // Process accounts, including related CashTransfer and JournalVoucher data
        $processedResults = [];
        foreach ($accounts as $account) {
            // Include account's opening balance as an initial row
            $openingBalanceRow = [
                'doc_date' => Carbon::parse($account->updated_at)->format('d-m-Y'), // No specific date for opening balance
                'type' => 'Accounts(Opening Balance)',
                'description' => $account->account_name,
                'debit' => $account->opening_balance,
                'credit' => 0,
                'balance' => $account->opening_balance,
            ];
            $processedResults[] = $openingBalanceRow;

            $previousBalance = $account->opening_balance;

            // Fetch related CashTransfers (incoming and outgoing)
            $cashTransfers = CashTransfer::where(function ($q) use ($account) {
                $q->where('from_account', $account->id)
                    ->orWhere('to_account', $account->id);
            })
                ->when(!empty($input['branch_id']), function ($q) use ($input) {
                    $q->where('branch_id', $input['branch_id']);
                })
                ->when(!empty($input['from_date']) && !empty($input['to_date']), function ($q) use ($input) {
                    $q->whereBetween('created_at', [$input['from_date'], $input['to_date']]);
                })
                ->when(!empty($input['from_date']), function ($q) use ($input) {
                    $q->where('created_at', '>=', $input['from_date']);
                })
                ->when(!empty($input['to_date']), function ($q) use ($input) {
                    $q->where('created_at', '<=', $input['to_date']);
                })
                ->get();

            foreach ($cashTransfers as $transfer) {
                $isIncoming = $transfer->to_account == $account->id;
                $amount = $transfer->transfer_amount;

                $row = [
                    'doc_date' => $transfer->created_at ? (new \DateTime($transfer->created_at))->format('d-m-Y') : '',
                    'type' => $isIncoming ? 'Cash Transfer (Incoming)' : 'Cash Transfer (Outgoing)',
                    'description' => $account->account_name,
                    'debit' => $isIncoming ? $amount : 0,
                    'credit' => $isIncoming ? 0 : $amount,
                    'balance' => $isIncoming ? $previousBalance + $amount : $previousBalance - $amount,
                ];

                $previousBalance = $row['balance'];
                $processedResults[] = $row;
            }

            // Fetch related JournalVouchers
            $journalVouchers = JournalVoucher::where('account_id', $account->id)
                ->when(!empty($input['branch_id']), function ($q) use ($input) {
                    $q->where('branch_id', $input['branch_id']);
                })
                ->when(!empty($input['from_date']) && !empty($input['to_date']), function ($q) use ($input) {
                    $q->whereBetween('created_at', [$input['from_date'], $input['to_date']]);
                })
                ->when(!empty($input['from_date']), function ($q) use ($input) {
                    $q->where('created_at', '>=', $input['from_date']);
                })
                ->when(!empty($input['to_date']), function ($q) use ($input) {
                    $q->where('created_at', '<=', $input['to_date']);
                })
                ->get();

            foreach ($journalVouchers as $voucher) {
                $row = [
                    'doc_date' => $voucher->created_at ? (new \DateTime($voucher->created_at))->format('d-m-Y') : '',
                    'type' => 'Journal Voucher',
                    'description' => $voucher->description ?? '',
                    'debit' => 0,
                    'credit' => $voucher->amount,
                    'balance' => $previousBalance - $voucher->amount,
                ];

                $previousBalance = $row['balance'];
                $processedResults[] = $row;
            }
        }

        return $processedResults;
    }
}
