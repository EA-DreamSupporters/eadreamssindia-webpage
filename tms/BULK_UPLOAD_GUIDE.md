# TMS Bulk Upload Feature - User Guide

## Overview

The Test Management System (TMS) now supports bulk upload of questions using Excel (.xlsx) and CSV (.csv) files. This feature is designed specifically for competitive exam questions with multi-line content, complex formatting, and detailed explanations.

## How to Use Bulk Upload

### Step 1: Download Template

1. Go to the Questions page in TMS
2. Click the "Bulk Upload" button
3. In the modal, click "Download Excel Template" or "Download CSV Template"
4. Save the template file to your computer

### Step 2: Fill the Template

Open the downloaded template and fill in your questions following this format:

#### Column Structure:

- **Subject**: Main subject (e.g., Polity, History, Geography)
- **Topic**: Specific topic within subject (e.g., Constitutional Law, Ancient India)
- **Subtopic**: More specific category (optional)
- **Question Text**: The complete question (supports multi-line text)
- **Option A**: First answer option
- **Option B**: Second answer option
- **Option C**: Third answer option
- **Option D**: Fourth answer option
- **Correct Answer**: A, B, C, or D
- **Explanation**: Detailed explanation of the correct answer
- **Difficulty**: easy, medium, or hard
- **Exam Year**: Year when question appeared (e.g., 2024)
- **Source/Exam**: Exam name or source (e.g., UPSC, SSC)
- **Is Public**: Yes or No (whether question is publicly visible)

#### Sample Question Format:

```
Subject: Polity
Topic: Constitutional Law
Subtopic: Writs
Question Text: With reference to the writs issued by the Courts in India, consider the following statements:

1. Mandamus will not lie against a private organization unless it is entrusted with a public duty.
2. Mandamus will not lie against a Company even though it may be a Government Company.
3. Any public minded person can be a petitioner to move the Court to obtain the writ of Quo Warranto.

Which of the statements given above are correct?

Option A: 1 and 2 only
Option B: 2 and 3 only
Option C: 1 and 3 only
Option D: 1, 2 and 3
Correct Answer: C
Explanation: Statement 1 is correct: Mandamus will not lie against a private organization unless it is entrusted with a public duty. Statement 2 is incorrect: Mandamus can lie against a Government Company as it performs public functions. Statement 3 is correct: Any public minded person can file for Quo Warranto to challenge illegal appointment to public office.
```

### Step 3: Upload and Preview

1. Click "Select File" or drag and drop your completed template
2. The system will parse and validate your questions
3. Review the preview to ensure all questions are correctly formatted
4. Check for any errors or warnings

### Step 4: Import Questions

1. If the preview looks correct, click "Import Questions"
2. The system will add all valid questions to your question bank
3. You'll see a success message with the number of questions imported

## Supported Formats

### Excel (.xlsx)

- Uses SpreadsheetML format for maximum compatibility
- Supports rich formatting and multi-line text
- Template includes proper styling and column widths
- Can be opened in Microsoft Excel, LibreOffice Calc, Google Sheets

### CSV (.csv)

- Plain text format, compatible with all spreadsheet applications
- Multi-line text supported using proper CSV escaping
- Smaller file size, good for large datasets
- Easy to generate programmatically

### Text Input

- Paste questions directly into the text area
- Supports various question numbering formats:
  - "1. Question text..."
  - "Q1. Question text..."
  - "Question 1: Text..."
- Automatically parses options (A. B. C. D.)
- Extracts answers and explanations

## Validation Rules

### Required Fields:

- Question Text (cannot be empty)
- At least one option (A, B, C, or D)
- Correct Answer (must be A, B, C, or D)

### Default Values:

- Subject: "General" (if not provided)
- Topic: "General" (if not provided)
- Difficulty: "medium" (if invalid value provided)
- Exam Year: Current year (if not provided)
- Is Public: "No" (if not specified)

### Error Handling:

- Invalid rows are skipped with error messages
- Partial imports are supported (valid questions are imported)
- Detailed error reporting for debugging

## Tips for Best Results

### Question Formatting:

1. **Multi-line Questions**: Use line breaks within cells for better readability
2. **Complex Options**: Include full option text, not just keywords
3. **Detailed Explanations**: Provide comprehensive explanations for learning
4. **Consistent Formatting**: Use the same style throughout your file

### Data Quality:

1. **Spell Check**: Review all content for spelling and grammar
2. **Answer Verification**: Double-check correct answers
3. **Categorization**: Use consistent subject/topic naming
4. **Source Attribution**: Include proper exam sources and years

### Performance:

1. **Batch Size**: Upload 50-100 questions at a time for best performance
2. **File Size**: Keep files under 5MB for smooth uploading
3. **Review Preview**: Always review the preview before importing

## Troubleshooting

### Common Issues:

**"Excel file format not supported"**

- Use the downloaded template format
- Ensure file has .xlsx extension
- Try saving as CSV if Excel parsing fails

**"Question text is required"**

- Check for empty question text cells
- Ensure questions are in the correct column

**"Invalid correct answer"**

- Correct answer must be exactly A, B, C, or D
- Check for extra spaces or lowercase letters

**"File upload failed"**

- Check file size (must be under 5MB)
- Ensure file extension is .xlsx or .csv
- Try clearing browser cache

### Getting Help:

If you encounter issues not covered here, contact the TMS administrator with:

- The template file you're using
- Error messages received
- Sample question data that's failing

## Technical Details

### File Processing:

- Excel files are parsed using SpreadsheetML XML format
- CSV files use standard RFC 4180 format
- Text input uses intelligent pattern recognition
- All uploads are validated before database insertion

### Security:

- File uploads are restricted to .xlsx and .csv only
- All input is sanitized and validated
- User authentication required for uploads
- Session-based preview storage for security

### Database Integration:

- Questions are inserted into the question_banks table
- Automatic institution assignment based on user context
- Duplicate detection and handling
- Transaction-based imports for data integrity

---

_Last Updated: January 2025_
_TMS Version: 1.0_
