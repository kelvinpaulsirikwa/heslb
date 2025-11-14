# Publication Seeder Files

This directory contains PDF files that will be automatically seeded into the publications system.

## How to Use

1. **Place your PDF files here** with the exact filenames specified in the PublicationSeeder
2. **Run the seeder** to automatically create publication records and copy files to storage

## File Naming Convention

Use the exact filenames as specified in the PublicationSeeder:

### HESLB Act & Amendments
- `heslb-act-2004.pdf`
- `heslb-act-amendments-2020.pdf`

### National Higher Education Policy
- `national-higher-education-policy-2021.pdf`

### Education Policy
- `education-policy-framework-2022.pdf`

### HESLB Strategic Plan
- `heslb-strategic-plan-2023-2028.pdf`

### Client Service Charter
- `heslb-client-service-charter.pdf`

### Complaints Handling Policy
- `complaints-handling-policy.pdf`

### HESLB Annual Reports and Financial Statements
- `heslb-annual-report-2023.pdf`
- `heslb-financial-statements-2023.pdf`

### Guidelines
- `student-loan-application-guidelines.pdf`
- `loan-repayment-guidelines.pdf`

### HESLB 20th Anniversary
- `heslb-20th-anniversary-book.pdf`

### HESLB Newsletters
- `heslb-newsletter-january-2024.pdf`
- `heslb-newsletter-february-2024.pdf`

## Adding New Publications

To add new publications:

1. **Add the file** to this directory with a descriptive filename
2. **Update PublicationSeeder.php** to include the new publication in the `$publications` array
3. **Run the seeder** again

## Running the Seeder

```bash
# Run all seeders (including publications)
php artisan db:seed

# Run only the publication seeder
php artisan db:seed --class=PublicationSeeder

# Fresh migration with all seeders
php artisan migrate:fresh --seed
```

## Notes

- Files are automatically copied to `public/storage/downloads/` during seeding
- File sizes are calculated automatically
- If a file is missing, a placeholder record is created
- All publications are set as active by default
- The seeder user is used as the `posted_by` field
