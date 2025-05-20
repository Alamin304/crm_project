<?php

namespace App\Imports;

use App\Models\Warranty;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class WarrantyImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Warranty([
            'claim_code' => $row['claim_code'],
            'customer' => $row['customer'],
            'invoice' => $row['invoice'] ?? null,
            'product_service_name' => $row['product_service_name'],
            'warranty_receipt_process' => $row['warranty_receipt_process'] ?? null,
            'description' => $row['description'] ?? null,
            'client_note' => $row['client_note'] ?? null,
            'admin_note' => $row['admin_note'] ?? null,
            'status' => $row['status'] ?? 'processing',
        ]);
    }

    public function rules(): array
    {
        return [
            '*.claim_code' => ['required', 'string', 'max:255', 'unique:warranties,claim_code'],
            '*.customer' => ['required', 'string', 'max:255'],
            '*.invoice' => ['nullable', 'string', 'max:255'],
            '*.product_service_name' => ['required', 'string', 'max:255'],
            '*.warranty_receipt_process' => ['nullable', 'string', 'max:255'],
            '*.description' => ['nullable', 'string'],
            '*.client_note' => ['nullable', 'string'],
            '*.admin_note' => ['nullable', 'string'],
            '*.status' => ['required', 'in:approved,processing,complete,closed,canceled'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.claim_code.required' => 'Claim code is required',
            '*.claim_code.unique' => 'This claim code already exists',
            '*.customer.required' => 'Customer name is required',
            '*.product_service_name.required' => 'Product/service name is required',
            '*.status.required' => 'Status is required',
            '*.status.in' => 'Status must be one of: approved, processing, complete, closed, canceled',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}