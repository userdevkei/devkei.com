<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Placeholder content: edit names, features and modules to match your real products.
        $products = [
            [
                'slug' => 'school-management', 'name' => 'KeiElimu School Management', 'category' => 'education',
                'tagline' => 'Admissions, fees, exams and parent communication in one system.',
                'summary' => 'A complete school management system for universities, colleges, secondary and primary schools, with fee collection tied to bank and mobile money payments.',
                'features' => ['Admissions and student records', 'Fee billing, statements and payment reconciliation', 'Exams, results and report forms', 'Parent and staff communication'],
                'modules' => ['Admissions', 'Fees', 'Academics', 'Exams', 'Communication', 'Reports'],
                'is_featured' => true, 'sort_order' => 1,
            ],
            [
                'slug' => 'hr-payroll', 'name' => 'DevKei HR and Payroll', 'category' => 'business',
                'tagline' => 'Staff records, leave and payroll with statutory deductions.',
                'summary' => 'Keep staff records, leave and payroll in one place, with payslips and statutory reports.',
                'features' => ['Employee records and contracts', 'Leave management', 'Payroll and payslips', 'Statutory deduction reports'],
                'modules' => ['Employees', 'Leave', 'Payroll', 'Reports'], 'sort_order' => 2,
            ],
            [
                'slug' => 'pos', 'name' => 'DevKei POS', 'category' => 'business',
                'tagline' => 'Fast checkout, receipts and daily cash-up across branches.',
                'summary' => 'A point of sale for shops and wholesalers that works with your stock and accounting.',
                'features' => ['Quick checkout and receipts', 'Multiple payment methods', 'Daily cash-up', 'Branch-level reporting'],
                'modules' => ['Sales', 'Receipts', 'Cash-up', 'Reports'], 'sort_order' => 3,
            ],
            [
                'slug' => 'stock-management', 'name' => 'DevKei Stock Management', 'category' => 'business',
                'tagline' => 'Purchasing, transfers, stock-takes and reorder levels.',
                'summary' => 'Track stock across stores and branches, from purchase to sale.',
                'features' => ['Purchase orders and receiving', 'Stock transfers', 'Stock-takes', 'Reorder alerts'],
                'modules' => ['Purchasing', 'Inventory', 'Transfers', 'Reports'], 'sort_order' => 4,
            ],
            [
                'slug' => 'accounting', 'name' => 'DevKei Accounting', 'category' => 'business',
                'tagline' => 'Ledgers, VAT and multi-currency reporting.',
                'summary' => 'A multi-currency accounting system with general ledger, VAT and financial reports.',
                'features' => ['General ledger', 'VAT reporting', 'Multi-currency and forex', 'Financial statements'],
                'modules' => ['General ledger', 'VAT', 'Forex', 'Reports'], 'sort_order' => 5,
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(['slug' => $data['slug']], $data + ['is_active' => true]);
        }
    }
}
