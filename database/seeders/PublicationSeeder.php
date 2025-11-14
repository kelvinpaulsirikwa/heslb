<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publication;
use App\Models\Category;
use App\Models\Userstable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the first user (seeder user) to use as posted_by
        $seederUser = Userstable::first();
        $postedBy = $seederUser ? $seederUser->id : 1;

        // Define ALL publications with their titles and file paths
        $publications = [
            // HESLB Act & Amendments
            [
                'title' => 'HESLB Act',
                'category' => 'HESLB Act & Amendments',
                'file_name' => 'HESLB_ACT.pdf',
                'description' => 'The Higher Education Students\' Loans Board Act',
                'publication_date' => '2004-01-01',
            ],
            [
                'title' => 'HESLB Act Amendment 2016',
                'category' => 'HESLB Act & Amendments',
                'file_name' => 'HESLB_ACT_AMENDMENT_2016.pdf',
                'description' => 'Amendment to the HESLB Act made in 2016',
                'publication_date' => '2016-01-01',
            ],

            // National Higher Education Policy
            [
                'title' => 'Higher Education Policy',
                'category' => 'National Higher Education Policy',
                'file_name' => 'HIGHER_EDUCATION_POLICY.pdf',
                'description' => 'National higher education policy document',
                'publication_date' => '2020-01-01',
            ],

            // Education Policy
            [
                'title' => 'Sera ya Elimu 2014',
                'category' => 'Education Policy',
                'file_name' => '220-sera-ya-elimu-2014.pdf',
                'description' => 'Education policy 2014 (Swahili)',
                'publication_date' => '2014-01-01',
            ],

            // HESLB Strategic Plan
            [
                'title' => 'Strategic Plan 2017',
                'category' => 'HESLB Strategic Plan',
                'file_name' => 'Strategic_Plan_2017.pdf',
                'description' => 'HESLB strategic plan for 2017',
                'publication_date' => '2017-01-01',
            ],

            // Client Service Charter
            [
                'title' => 'Client Service Charter',
                'category' => 'Client Service Charter',
                'file_name' => 'Client Service Charter.pdf',
                'description' => 'HESLB client service charter and commitments',
                'publication_date' => '2020-01-01',
            ],

            // Complaints Handling Policy
            [
                'title' => 'HESLB Complaints Handling Policy',
                'category' => 'Complaints Handling Policy',
                'file_name' => 'HESLB_COMPLAINTS_HANDLING_POLICY-ONLINE.pdf',
                'description' => 'Policies and procedures for handling complaints',
                'publication_date' => '2020-01-01',
            ],

            // HESLB Annual Reports and Financial Statements
            [
                'title' => 'Annual Report 2016-2017',
                'category' => 'HESLB Annual Reports and Financial Statements',
                'file_name' => 'AnnualReport2016_2017.pdf',
                'description' => 'HESLB annual report for 2016-2017',
                'publication_date' => '2017-01-01',
            ],
            [
                'title' => 'Annual Report 2017-2018',
                'category' => 'HESLB Annual Reports and Financial Statements',
                'file_name' => 'AnnualReport2017_2018.pdf',
                'description' => 'HESLB annual report for 2017-2018',
                'publication_date' => '2018-01-01',
            ],
            [
                'title' => 'Annual Report DLAD 2025',
                'category' => 'HESLB Annual Reports and Financial Statements',
                'file_name' => 'Anual_Report_DLAD_2025.pdf',
                'description' => 'Annual report DLAD for 2025',
                'publication_date' => '2025-01-01',
            ],
            [
                'title' => 'HESLB Annual Report 2019',
                'category' => 'HESLB Annual Reports and Financial Statements',
                'file_name' => 'HESLB_-_2019.pdf',
                'description' => 'HESLB annual report for 2019',
                'publication_date' => '2019-01-01',
            ],
            [
                'title' => 'HESLB Financial Statements 2020',
                'category' => 'HESLB Annual Reports and Financial Statements',
                'file_name' => 'HESLB_-FS_2020.pdf',
                'description' => 'HESLB financial statements for 2020',
                'publication_date' => '2020-01-01',
            ],
            [
                'title' => 'HESLB Financial Statements 2021',
                'category' => 'HESLB Annual Reports and Financial Statements',
                'file_name' => 'HESLB_FS_2021.pdf',
                'description' => 'HESLB financial statements for 2021',
                'publication_date' => '2021-01-01',
            ],
            [
                'title' => 'HESLB Financial Statements 2023',
                'category' => 'HESLB Annual Reports and Financial Statements',
                'file_name' => 'HESLB_FS_2023.pdf',
                'description' => 'HESLB financial statements for 2023',
                'publication_date' => '2023-01-01',
            ],
            [
                'title' => 'HESLB Annual Report 2021-2022',
                'category' => 'HESLB Annual Reports and Financial Statements',
                'file_name' => 'HESLB-2021-22.pdf',
                'description' => 'HESLB annual report for 2021-2022',
                'publication_date' => '2022-01-01',
            ],

            // Guidelines
            [
                'title' => 'GUIDELINES AND CRITERIA 2009-2010',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2009_2010.pdf',
                'description' => 'Guidelines and criteria for student loans 2009-2010',
                'publication_date' => '2009-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2009-2010 (Alternative)',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2009-2010.pdf',
                'description' => 'Guidelines and criteria for student loans 2009-2010 (alternative version)',
                'publication_date' => '2009-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2010-2011',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2010_2011.pdf',
                'description' => 'Guidelines and criteria for student loans 2010-2011',
                'publication_date' => '2010-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2010-2011 (Alternative)',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2010-2011.pdf',
                'description' => 'Guidelines and criteria for student loans 2010-2011 (alternative version)',
                'publication_date' => '2010-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2011-2012',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2011_2012.pdf',
                'description' => 'Guidelines and criteria for student loans 2011-2012',
                'publication_date' => '2011-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2011-2012 (Alternative)',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2011-2012.pdf',
                'description' => 'Guidelines and criteria for student loans 2011-2012 (alternative version)',
                'publication_date' => '2011-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2012-2013',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2012_2013.pdf',
                'description' => 'Guidelines and criteria for student loans 2012-2013',
                'publication_date' => '2012-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2012-2013 (Alternative)',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2012-2013.pdf',
                'description' => 'Guidelines and criteria for student loans 2012-2013 (alternative version)',
                'publication_date' => '2012-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2013-2014',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2013_2014.pdf',
                'description' => 'Guidelines and criteria for student loans 2013-2014',
                'publication_date' => '2013-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2014-2015',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2014_2015.pdf',
                'description' => 'Guidelines and criteria for student loans 2014-2015',
                'publication_date' => '2014-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2015-2016',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2015_2016.pdf',
                'description' => 'Guidelines and criteria for student loans 2015-2016',
                'publication_date' => '2015-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2016-2017',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2016_2017.pdf',
                'description' => 'Guidelines and criteria for student loans 2016-2017',
                'publication_date' => '2016-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2017-2018',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2017_2018.pdf',
                'description' => 'Guidelines and criteria for student loans 2017-2018',
                'publication_date' => '2017-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2018-2019',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2018_2019.pdf',
                'description' => 'Guidelines and criteria for student loans 2018-2019',
                'publication_date' => '2018-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2019-2020',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2019_2020.pdf',
                'description' => 'Guidelines and criteria for student loans 2019-2020',
                'publication_date' => '2019-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2020-2021',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2020_2021.pdf',
                'description' => 'Guidelines and criteria for student loans 2020-2021',
                'publication_date' => '2020-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2021-2022',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2021_2022.pdf',
                'description' => 'Guidelines and criteria for student loans 2021-2022',
                'publication_date' => '2021-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2022-2023',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2022_2023.pdf',
                'description' => 'Guidelines and criteria for student loans 2022-2023',
                'publication_date' => '2022-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2022-2023 (Alternative)',
                'category' => 'Guidelines',
                'file_name' => 'GUIDELINES_AND_CRITERIA_2022-2023.pdf',
                'description' => 'Guidelines and criteria for student loans 2022-2023 (alternative version)',
                'publication_date' => '2022-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA FOR BACHELOR DEGREES 2024-2025',
                'category' => 'Guidelines',
                'file_name' => 'Guidelines_and_Criteria_for_Bachelor_Degree_2024_2025.pdf',
                'description' => 'Guidelines and criteria for bachelor degrees 2024-2025',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA FOR DIPLOMA 2024-2025',
                'category' => 'Guidelines',
                'file_name' => 'Guidelines_and_Criteria_for_Loans_Issuance_-_Diploma_2024_2025.pdf',
                'description' => 'Guidelines and criteria for diploma 2024-2025',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'MASTERS & PhD GUIDELINES 2024-2025',
                'category' => 'Guidelines',
                'file_name' => 'Guidelines_and_Criteria_for_Loans_Issuance_-_Masters_PhD_2024_2025.pdf',
                'description' => 'Guidelines and criteria for masters and PhD studies 2024-2025',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'GUIDELINES FOR SAMIA SCHOLARSHIP 2024 - 2025',
                'category' => 'Guidelines',
                'file_name' => 'Guidelines_for_2024-2025_Samia_Scholarship_-_English_08072024.pdf',
                'description' => 'Guidelines for Samia scholarship 2024-2025',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA FOR POST GRADUATE DIPLOMA IN LEGAL PRACTICE 2024-2025',
                'category' => 'Guidelines',
                'file_name' => 'Guidelines_for_Post_Graduate_Diploma_in_Legal_Practice_2024_2025.pdf',
                'description' => 'Guidelines and criteria for post graduate diploma in legal practice 2024-2025',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'LIST FOR SAMIA SCHOLARSHIP 2023-2024',
                'category' => 'Guidelines',
                'file_name' => 'LIST_FOR_SAMIA_SCHOLARSHIP_2023-2024.pdf',
                'description' => 'List of qualified students for Samia scholarship 2023-2024',
                'publication_date' => '2023-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA 2023-2024',
                'category' => 'Guidelines',
                'file_name' => 'LUG_Guidelines_2023_-_2024.pdf',
                'description' => 'Guidelines and criteria for student loans 2023-2024',
                'publication_date' => '2023-01-01',
            ],
            [
                'title' => 'LIST FOR SAMIA SCHOLARSHIP 2025 - 2026',
                'category' => 'Guidelines',
                'file_name' => 'MAJINA_YA_WANAFUNZI_SAMIA_SCHOLARSHIP_2025_-2026.pdf',
                'description' => 'List of students for Samia scholarship 2025-2026',
                'publication_date' => '2025-01-01',
            ],
            [
                'title' => 'FREQUENTLY ASKED QUESTIONS 2024/2025',
                'category' => 'Guidelines',
                'file_name' => 'Maswali_na_Majibu_2024-2025.pdf',
                'description' => 'Frequently asked questions for 2024/2025 academic year',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA FOR DIPLOMA (2nd Edition) 2023-2024',
                'category' => 'Guidelines',
                'file_name' => 'Mkopo_wa_Stashahada_-_Toleo_la_pili.pdf',
                'description' => 'Second edition guidelines and criteria for diploma 2023-2024',
                'publication_date' => '2023-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA FOR BACHELOR DEGREES 2025-2026',
                'category' => 'Guidelines',
                'file_name' => 'NEW_-_BACHELOR\'S_DEGREE_STUDENTS_FOR_2025_2026.pdf',
                'description' => 'Guidelines and criteria for bachelor degrees 2025-2026',
                'publication_date' => '2025-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA FOR DIPLOMA 2025-2026',
                'category' => 'Guidelines',
                'file_name' => 'New_-_Guidelines_and_Criteria_to_Diploma_Students_20252026.pdf',
                'description' => 'Guidelines and criteria for diploma students 2025-2026',
                'publication_date' => '2025-01-01',
            ],
            [
                'title' => 'MASTERS & PhD GUIDELINES 2025-2026',
                'category' => 'Guidelines',
                'file_name' => 'New_-_MASTERS_AND_PhD_STUDIES_2025_2026.pdf',
                'description' => 'Guidelines and criteria for masters and PhD studies 2025-2026',
                'publication_date' => '2025-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA TO SAMIA SCHOLARSHIP STUDENTS 2025-2026',
                'category' => 'Guidelines',
                'file_name' => 'NEW_-_SAMIA_SCHOLARSHIP_STUDENTS_2025-2026.pdf',
                'description' => 'Guidelines and criteria for Samia scholarship students 2025-2026',
                'publication_date' => '2025-01-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA FOR POST GRADUATE DIPLOMA IN LEGAL PRACTICE 2025-2026',
                'category' => 'Guidelines',
                'file_name' => 'NEW_-POSTGRADUATE_DIPLOMA_IN_LEGAL_PRACTICE_2025_2026.pdf',
                'description' => 'Guidelines and criteria for post graduate diploma in legal practice 2025-2026',
                'publication_date' => '2025-01-01',
            ],
            [
                'title' => 'ORODHA MPYA YA WANAWAFUNZI WA SAMIA SKOLASHIPU 2022 -2023',
                'category' => 'Guidelines',
                'file_name' => 'ORODHA_MPYA_YA_WANAFUNZI_WA_SAMIA_SKOLASHIPU.pdf',
                'description' => 'Updated list of Samia scholarship students 2022-2023',
                'publication_date' => '2022-01-01',
            ],
            [
                'title' => 'ORODHA YA PROGRAMU ZA STASHAHADA 2024 - 2025',
                'category' => 'Guidelines',
                'file_name' => 'ORODHA_YA_PROGRAMU_ZA_STASHAHADA_2024.pdf',
                'description' => 'List of diploma programmes 2024-2025',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'LIST OF DIPLOMA COLLEGES WITH STUDENTS ALLOCATED LOANS 2023-2024',
                'category' => 'Guidelines',
                'file_name' => 'ORODHA_YA_VYUO_KWA_WANAFUNZI_DIPLOMA_2023.pdf',
                'description' => 'List of diploma colleges with students allocated loans 2023-2024',
                'publication_date' => '2023-01-01',
            ],
            [
                'title' => 'OVERSEAS GUIDELINES 2022-2023',
                'category' => 'Guidelines',
                'file_name' => 'Overseas_Loan_Guideline-_revised.pdf',
                'description' => 'Overseas loan guidelines and criteria 2022-2023',
                'publication_date' => '2022-01-01',
            ],
            [
                'title' => 'POSTGRADUATE GUIDELINES 2023-2024',
                'category' => 'Guidelines',
                'file_name' => 'PGD_Guideline_2023_-_2024.pdf',
                'description' => 'Postgraduate loan guidelines and criteria 2023-2024',
                'publication_date' => '2023-01-01',
            ],
            [
                'title' => 'POSTGRADUATE GUIDELINES 2022-2023',
                'category' => 'Guidelines',
                'file_name' => 'Postgraduate_Loan_Guideline-revised.pdf',
                'description' => 'Postgraduate loan guidelines and criteria 2022-2023',
                'publication_date' => '2022-01-01',
            ],
            [
                'title' => 'REQUIREMENTS FOR REGISTRATION OF CORPORATE SPONSORS',
                'category' => 'Guidelines',
                'file_name' => 'REQUIREMENTS_FOR_REGISTRATION_OF_CORPORATE_SPONSORS_-_18062025.pdf',
                'description' => 'Requirements for registration of corporate sponsors',
                'publication_date' => '2025-06-18',
            ],
            [
                'title' => 'SAMIA SCHOLARSHIP',
                'category' => 'Guidelines',
                'file_name' => 'SAMIA_SCHOLARSHIPS_PDF_FINAL.pdf',
                'description' => 'Samia scholarship guidelines and criteria',
                'publication_date' => '2022-01-01',
            ],
            [
                'title' => 'SECOND EDITION: GUIDELINES AND CRITERIA FOR DIPLOMA 2024-2025',
                'category' => 'Guidelines',
                'file_name' => 'Second_Edition_Guidelines_for_Diploma_2024_2025.pdf',
                'description' => 'Second edition guidelines and criteria for diploma 2024-2025',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'QUALIFIED SAMIA SCHOLARSHIP APPLICANTS FOR MASTER DEGREE PROGRAMMES',
                'category' => 'Guidelines',
                'file_name' => 'The_Final_Samia_scholarship_list-Website_-_DEC_2024.pdf',
                'description' => 'Final list of qualified Samia scholarship applicants for master degree programmes',
                'publication_date' => '2024-12-01',
            ],
            [
                'title' => 'GUIDELINES AND CRITERIA FOR DIPLOMA 2023-2024',
                'category' => 'Guidelines',
                'file_name' => 'UZINDUZI_MWONGOZO_DIPLOMA_PDF.pdf',
                'description' => 'Guidelines and criteria for diploma students 2023-2024',
                'publication_date' => '2023-01-01',
            ],
            [
                'title' => 'LUG Guidelines After MoEST Endorsement',
                'category' => 'Guidelines',
                'file_name' => 'Ver_2_-_LUG_Guidelines_After_MoEST_Endorsement_13072023.pdf',
                'description' => 'LUG guidelines after MoEST endorsement',
                'publication_date' => '2023-07-13',
            ],
            [
                'title' => 'SECOND LIST FOR SAMIA SCHOLARSHIP 2023-2024',
                'category' => 'Guidelines',
                'file_name' => 'AGREED_SECOND_LIST_OF_QUALIFIED_STUDENTS_FOR_SAMIA_SCHOLARSHIP.pdf',
                'description' => 'Second list of qualified students for Samia scholarship 2023-2024',
                'publication_date' => '2023-01-01',
            ],
            [
                'title' => 'CORPORATE SPONSORSHIP FORM',
                'category' => 'Guidelines',
                'file_name' => 'CORPORAT_SPONSORSHIP_FORM.pdf',
                'description' => 'Corporate sponsorship form and guidelines',
                'publication_date' => '2022-01-01',
            ],
            [
                'title' => 'DISABILITY FORM',
                'category' => 'Guidelines',
                'file_name' => 'DISABILITY_FORM.pdf',
                'description' => 'Disability form and guidelines',
                'publication_date' => '2022-01-01',
            ],

            // HESLB 20th Anniversary
            [
                'title' => 'HESLB@20 Sponsorship Catalogue',
                'category' => 'HESLB 20th Anniversary',
                'file_name' => 'HESLB@20_Sponsorship_Catalogue.pdf',
                'description' => 'Sponsorship catalogue for HESLB 20th anniversary',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'Hotuba ya Waziri Mkuu - Miaka 20 ya HESLB',
                'category' => 'HESLB 20th Anniversary',
                'file_name' => 'HOTUBA_YA_WAZIRI_MKUU_-_MIAKA_20_YA_HESLB.pdf',
                'description' => 'Prime Minister\'s speech for HESLB 20th anniversary',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'Taarifa za Mkutano HESLB@20',
                'category' => 'HESLB 20th Anniversary',
                'file_name' => 'TAARIFA_ZA_MKUTANO_HESLB@20.pdf',
                'description' => 'Meeting information for HESLB@20',
                'publication_date' => '2024-01-01',
            ],

            // HESLB Newsletters
            [
                'title' => 'HESLB YAKO - 1',
                'category' => 'HESLB Newsletters',
                'file_name' => 'HESLB_YAKO_1.pdf',
                'description' => 'HESLB Yako Newsletter Issue 1',
                'publication_date' => '2020-01-01',
            ],
            [
                'title' => 'HESLB YAKO - 2',
                'category' => 'HESLB Newsletters',
                'file_name' => 'HESLB_YAKO_2.pdf',
                'description' => 'HESLB Yako Newsletter Issue 2',
                'publication_date' => '2020-02-01',
            ],
            [
                'title' => 'HESLB_Yako-4',
                'category' => 'HESLB Newsletters',
                'file_name' => 'HESLB_Yako_4.pdf',
                'description' => 'HESLB Yako Newsletter Issue 4',
                'publication_date' => '2020-04-01',
            ],
            [
                'title' => 'HESLB YAKO 7',
                'category' => 'HESLB Newsletters',
                'file_name' => 'Newsletter_ISSUE_SEVEN.pdf',
                'description' => 'HESLB Yako Newsletter Issue 7',
                'publication_date' => '2021-07-01',
            ],
            [
                'title' => 'HESLB YAKO 9',
                'category' => 'HESLB Newsletters',
                'file_name' => 'Newsletter_ISSUE_NINE-Final.pdf',
                'description' => 'HESLB Yako Newsletter Issue 9',
                'publication_date' => '2021-09-01',
            ],
            [
                'title' => 'HESLB YAKO 10',
                'category' => 'HESLB Newsletters',
                'file_name' => 'Newsletter_ISSUE_TEN-Final.pdf',
                'description' => 'HESLB Yako Newsletter Issue 10',
                'publication_date' => '2021-10-01',
            ],
            [
                'title' => 'HESLB YAKO 11',
                'category' => 'HESLB Newsletters',
                'file_name' => 'FINAL_23OCT,2023-Newsletter_ISSUE_Eleven.pdf',
                'description' => 'HESLB Yako Newsletter Issue 11',
                'publication_date' => '2023-10-23',
            ],
            [
                'title' => 'HESLB YAKO 12',
                'category' => 'HESLB Newsletters',
                'file_name' => 'EiM-Newsletter_ISSUE_Twelve.pdf',
                'description' => 'HESLB Yako Newsletter Issue 12',
                'publication_date' => '2023-12-01',
            ],
            [
                'title' => 'HESLB YAKO 13',
                'category' => 'HESLB Newsletters',
                'file_name' => 'HESLB_Yako_Toleo_la_13.pdf',
                'description' => 'HESLB Yako Newsletter Issue 13',
                'publication_date' => '2024-01-01',
            ],
            [
                'title' => 'HESLB YAKO 14',
                'category' => 'HESLB Newsletters',
                'file_name' => 'Newsletter_ISSUE_14.pdf',
                'description' => 'HESLB Yako Newsletter Issue 14',
                'publication_date' => '2024-02-01',
            ],
            [
                'title' => 'HESLB YAKO 15',
                'category' => 'HESLB Newsletters',
                'file_name' => 'Jarida_la_HESLB_Yako,_Toleo_namba_15_pdf.pdf',
                'description' => 'HESLB Yako Newsletter Issue 15',
                'publication_date' => '2024-03-01',
            ],
            [
                'title' => 'HESLB YAKO 16',
                'category' => 'HESLB Newsletters',
                'file_name' => 'Revised_Newsletter_ISSUE_16-2-1.pdf',
                'description' => 'HESLB Yako Newsletter Issue 16',
                'publication_date' => '2024-04-01',
            ],
        ];

        // Create each publication
        foreach ($publications as $publicationData) {
            // Find the category
            $category = Category::where('name', $publicationData['category'])->first();
            
            if (!$category) {
                $this->command->warn("Category '{$publicationData['category']}' not found. Skipping publication '{$publicationData['title']}'");
                continue;
            }

            // Determine the correct folder path based on category
            $folderPath = '';
            switch ($publicationData['category']) {
                case 'HESLB Act & Amendments':
                    $folderPath = '1-heslb-act-amendments';
                    break;
                case 'National Higher Education Policy':
                    $folderPath = '2-national-higher-education-policy';
                    break;
                case 'Education Policy':
                    $folderPath = '3-education-policy';
                    break;
                case 'HESLB Strategic Plan':
                    $folderPath = '4-heslb-strategic-plan';
                    break;
                case 'Client Service Charter':
                    $folderPath = '5-client-service-charter';
                    break;
                case 'Complaints Handling Policy':
                    $folderPath = '6-complaints-handling-policy';
                    break;
                case 'HESLB Annual Reports and Financial Statements':
                    $folderPath = '7-heslb-annual-reports-financial-statements';
                    break;
                case 'Guidelines':
                    $folderPath = '8-guidelines';
                    break;
                case 'HESLB 20th Anniversary':
                    $folderPath = '9-heslb-20th-anniversary';
                    break;
                case 'HESLB Newsletters':
                    $folderPath = '10-heslb-newsletters';
                    break;
            }

            // Check if file exists in seed-files directory
            $seedFilePath = database_path("seed-files/publications/{$folderPath}/" . $publicationData['file_name']);
            $fileExists = file_exists($seedFilePath);

            if ($fileExists) {
                // Copy file to storage
                $fileName = time() . '_' . Str::slug(pathinfo($publicationData['file_name'], PATHINFO_FILENAME)) . '.pdf';
                $storagePath = 'downloads/' . $fileName;
                
                // Ensure downloads directory exists
                Storage::disk('public')->makeDirectory('downloads');
                
                // Copy file to storage
                Storage::disk('public')->put($storagePath, file_get_contents($seedFilePath));
                
                $filePath = '/images/storage/' . $storagePath;
                $fileSize = round(filesize($seedFilePath) / 1024); // Size in KB
            } else {
                // File doesn't exist, create placeholder
                $this->command->warn("File '{$publicationData['file_name']}' not found in seed-files. Creating placeholder.");
                $fileName = $publicationData['file_name'];
                $filePath = null;
                $fileSize = null;
            }

            // Create publication record
            Publication::create([
                'title' => $publicationData['title'],
                'category_id' => $category->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => 'PDF',
                'file_size' => $fileSize,
                'description' => $publicationData['description'],
                'publication_date' => Carbon::parse($publicationData['publication_date']),
                'posted_by' => $postedBy,
                'is_active' => true,
                'download_count' => 0,
            ]);
        }

        $this->command->info('Publications seeded successfully!');
        $this->command->info('Found and processed ' . count($publications) . ' PDF files.');
    }
}