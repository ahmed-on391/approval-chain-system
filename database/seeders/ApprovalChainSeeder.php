<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApprovalChain;


class ApprovalChainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 7; $i++) {
            ApprovalChain::create([
                'project_id' => 3,
                'user_id' => 1,
                'status' => 'Pending',
                'position' => 1,
            ]);
        }
    }
}