<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FAQ;
use App\Models\Userstable;

class FAQSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$adminUser = Userstable::where('role', 'admin')->first();
		if (!$adminUser) {
			return; // No admin user to attribute posts to
		}

		// Include the seed files which define $faqs and $loanRepaymentFaqs
		$applicationFile = database_path('seed-files/faq/loanapplication.php');
		$repaymentFile = database_path('seed-files/faq/loanrepayment.php');

		$faqs = [];
		$loanRepaymentFaqs = [];

		if (file_exists($applicationFile)) {
			@include $applicationFile; // populates $faqs
		}
		if (file_exists($repaymentFile)) {
			@include $repaymentFile; // populates $loanRepaymentFaqs
		}

		// Seed loan application FAQs
		if (!empty($faqs)) {
			$this->seedFaqGroup($faqs, 'loan_application', (int) $adminUser->id);
		}

		// Seed loan repayment FAQs
		if (!empty($loanRepaymentFaqs)) {
			$this->seedFaqGroup($loanRepaymentFaqs, 'loan_repayment', (int) $adminUser->id);
		}
	}

	/**
	 * Seed a FAQ group by mapping keys to qnstype enums and inserting.
	 *
	 * @param array $groupData
	 * @param string $typeEnum
	 * @param int $adminUserId
	 * @return void
	 */
	protected function seedFaqGroup(array $groupData, string $typeEnum, int $adminUserId): void
	{
		$mapping = [
			'popularqns' => 'popular_questions',
			'generalqns' => 'general_questions',
		];

		foreach ($groupData as $key => $items) {
			$qnsType = $mapping[$key] ?? null;
			if (!$qnsType || !is_array($items)) {
				continue;
			}

			foreach ($items as $item) {
				if (!isset($item['question']) || !isset($item['answer'])) {
					continue;
				}

				FAQ::updateOrCreate(
					[
						'question' => $item['question'],
						'type' => $typeEnum,
						'qnstype' => $qnsType,
					],
					[
						'answer' => json_encode($item['answer']),
						'posted_by' => $adminUserId,
					]
				);
			}
		}
	}
}


