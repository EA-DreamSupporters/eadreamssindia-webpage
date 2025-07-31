<?php
// Standalone template generator

function createExcelTemplate() {
    // Create Excel content with company name row and serial number column using SpreadsheetML format
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:html="http://www.w3.org/TR/REC-html40">
    <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
        <Title>Competitive Exam Questions Template</Title>
        <Author>EA Dreams TMS</Author>
        <Created>' . date('Y-m-d\TH:i:s\Z') . '</Created>
    </DocumentProperties>
    <Styles>
        <Style ss:ID="companyHeader">
        <Font ss:Bold="1"ss:Size="16"/><Interior ss:Color="#1f4e79"ss:Pattern="Solid"/><Font ss:Color="#FFFFFF"/><Alignment ss:Horizontal="Center"ss:Vertical="Center"/><Borders><Border ss:Position="Bottom"ss:LineStyle="Continuous"ss:Weight="2"/><Border ss:Position="Left"ss:LineStyle="Continuous"ss:Weight="2"/><Border ss:Position="Right"ss:LineStyle="Continuous"ss:Weight="2"/><Border ss:Position="Top"ss:LineStyle="Continuous"ss:Weight="2"/></Borders>
        </Style>
        <Style ss:ID="header">
        <Font ss:Bold="1"ss:Size="12"/><Interior ss:Color="#4472C4"ss:Pattern="Solid"/><Font ss:Color="#FFFFFF"/><Alignment ss:Horizontal="Center"ss:Vertical="Center"/><Borders><Border ss:Position="Bottom"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Left"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Right"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Top"ss:LineStyle="Continuous"ss:Weight="1"/></Borders>
        </Style>
        <Style ss:ID="cell">
        <Borders><Border ss:Position="Bottom"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Left"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Right"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Top"ss:LineStyle="Continuous"ss:Weight="1"/></Borders><Alignment ss:WrapText="1"ss:Vertical="Top"/>
        </Style>
        <Style ss:ID="serialCell">
        <Borders><Border ss:Position="Bottom"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Left"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Right"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Top"ss:LineStyle="Continuous"ss:Weight="1"/></Borders><Alignment ss:Horizontal="Center"ss:Vertical="Center"/><Font ss:Bold="1"/>
        </Style>
        <Style ss:ID="question">
        <Borders><Border ss:Position="Bottom"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Left"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Right"ss:LineStyle="Continuous"ss:Weight="1"/><Border ss:Position="Top"ss:LineStyle="Continuous"ss:Weight="1"/></Borders><Alignment ss:WrapText="1"ss:Vertical="Top"/>
        </Style>
    </Styles>
    <Worksheet ss:Name="Question Template">
        <Table>
            <Column ss:Width="60" />
            <Column ss:Width="100" />
            <Column ss:Width="120" />
            <Column ss:Width="100" />
            <Column ss:Width="400" />
            <Column ss:Width="150" />
            <Column ss:Width="150" />
            <Column ss:Width="150" />
            <Column ss:Width="150" />
            <Column ss:Width="80" />
            <Column ss:Width="300" />
            <Column ss:Width="80" />
            <Column ss:Width="80" />
            <Column ss:Width="100" />
            <Column ss:Width="80" />

            <!-- Company Name Row -->
            <Row ss:Height="40">
                <Cell ss:StyleID="companyHeader" ss:MergeAcross="14"><Data ss:Type="String">[ENTER YOUR
                        COMPANY/INSTITUTION NAME HERE]</Data></Cell>
            </Row>

            <!-- Empty Row for spacing -->
            <Row ss:Height="20">
                <Cell></Cell>
            </Row>

            <!-- Header Row -->
            <Row ss:Height="30">
                <Cell ss:StyleID="header"><Data ss:Type="String">S.No</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Subject</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Topic</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Subtopic</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Question Text</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Option A</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Option B</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Option C</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Option D</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Correct Answer</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Explanation</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Difficulty</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Exam Year</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Source/Exam</Data></Cell>
                <Cell ss:StyleID="header"><Data ss:Type="String">Is Public</Data></Cell>
            </Row>

            <!-- Sample Data Row 1 -->
            <Row>
                <Cell ss:StyleID="serialCell"><Data ss:Type="Number">1</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Polity</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Constitutional Law</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Writs</Data></Cell>
                <Cell ss:StyleID="question"><Data ss:Type="String">With reference to the writs issued by the Courts in
                        India, consider the following statements:

                        1. Mandamus will not lie against a private organization unless it is entrusted with a public
                        duty.
                        2. Mandamus will not lie against a Company even though it may be a Government Company.
                        3. Any public minded person can be a petitioner to move the Court to obtain the writ of Quo
                        Warranto.

                        Which of the statements given above are correct?</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">1 and 2 only</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">2 and 3 only</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">1 and 3 only</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">1, 2 and 3</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">C</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Statement 1 is correct: Mandamus will not lie against a
                        private organization unless it is entrusted with a public duty. Statement 2 is incorrect:
                        Mandamus can lie against a Government Company as it performs public functions. Statement 3 is
                        correct: Any public minded person can file for Quo Warranto to challenge illegal appointment to
                        public office.</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">medium</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">2024</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">UPSC</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Yes</Data></Cell>
            </Row>

            <!-- Sample Data Row 2 -->
            <Row>
                <Cell ss:StyleID="serialCell"><Data ss:Type="Number">2</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Reasoning</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Logical Reasoning</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Series</Data></Cell>
                <Cell ss:StyleID="question"><Data ss:Type="String">In the series: 2, 6, 12, 20, 30, ?

                        What comes next?</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">42</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">40</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">36</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">48</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">A</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">The pattern is: 2=1×2, 6=2×3, 12=3×4, 20=4×5, 30=5×6.
                        Next would be 6×7=42</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">easy</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">2024</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">SSC</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String">Yes</Data></Cell>
            </Row>';

            // Add empty rows with auto-incrementing serial numbers
            for ($i = 3; $i <= 12; $i++) { $xml .='
            <Row>
                <Cell ss:StyleID="serialCell"><Data ss:Type="Number">' . $i . '</Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
                <Cell ss:StyleID="cell"><Data ss:Type="String"></Data></Cell>
            </Row>' ; } $xml .='
        </Table>
        <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
            <FreezePanes/>
            <FrozenNoSplit/>
            <SplitHorizontal>3</SplitHorizontal>
            <TopRowBottomPane>3</TopRowBottomPane>
        </WorksheetOptions>
    </Worksheet>
</Workbook>' ; return $xml; } // Generate the template file_put_contents('sample_excel_template_new.xlsx',
                createExcelTemplate(); echo "✓ New Excel template generated successfully!\n" ;
                echo "File: sample_excel_template_new.xlsx\n" ; echo "Size: " .
                filesize('sample_excel_template_new.xlsx') . " bytes\n" ; // Test the content
                $content=file_get_contents('sample_excel_template_new.xlsx'); if (strpos($content, '[ENTER YOUR COMPANY'
                ) !==false) { echo "✓ Company name placeholder found\n" ; } if (strpos($content, 'S.No' ) !==false) {
                echo "✓ S.No column found\n" ; } echo "✓ Template ready for use!\n" ; ?>